<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Menu;
use App\Models\AppRoute;
use App\Models\Permission;

class AccessControlController extends Controller
{
    public function index()
    {
        // Load Role beserta permission-nya agar bisa dicek di Blade
        $roles = Role::with(['users', 'permissions'])->get();

        // Load Menu beserta permission-nya
        $menus = Menu::orderBy('order')->get();

        $appRoutes = AppRoute::all();

        // Permission dikelompokkan berdasarkan Menu (menggunakan relation di Model Permission)
        // Pastikan Model Permission punya method menu()
        $permissionsGrouped = Permission::with('menu')->get()->groupBy('menu_id');

        return view('dashboard.manajemen_akses.index', compact('roles', 'menus', 'appRoutes', 'permissionsGrouped'));
    }

    // --- LOGIC ROLE ---
    public function storeRole(Request $request)
    {
        Role::create($request->validate([
            'name' => 'required|unique:roles,name',
            'description' => 'nullable'
        ]));
        return back()->with('success', 'Role berhasil ditambahkan');
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'description' => 'nullable'
        ]));
        return back()->with('success', 'Role berhasil diupdate');
    }

    public function destroyRole($id)
    {
        Role::findOrFail($id)->delete();
        return back()->with('success', 'Role berhasil dihapus');
    }

    // --- LOGIC MENU ---
    public function storeMenu(Request $request)
    {
        // 1. Validasi Input (Agar data bersih)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string',
            'route_name' => 'required|string',
            'icon' => 'nullable|string',
        ]);

        // 2. Simpan Menu ke Database
        $menu = Menu::create($validated);

        // ==========================================================
        // FITUR OTOMATIS: Berikan akses menu ini ke Role 'admin'
        // ==========================================================

        // Cari Role yang namanya 'admin'
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            // Simpan ke tabel pivot (role_has_menus)
            $adminRole->menus()->attach($menu->id);
        }

        return back()->with('success', 'Menu berhasil dibuat & otomatis aktif untuk Admin.');
    }

    public function updateMenu(Request $request, $id)
    {
        Menu::findOrFail($id)->update($request->all());
        return back()->with('success', 'Menu berhasil diupdate');
    }

    public function destroyMenu($id)
    {
        Menu::findOrFail($id)->delete();
        return back()->with('success', 'Menu berhasil dihapus');
    }

    // --- LOGIC ROUTES ---
    public function storeRoute(Request $request)
    {
        AppRoute::create($request->all());
        return back()->with('success', 'Route berhasil ditambahkan');
    }

    public function updateRoute(Request $request, $id)
    {
        AppRoute::findOrFail($id)->update($request->all());
        return back()->with('success', 'Route berhasil diupdate');
    }

    public function destroyRoute($id)
    {
        AppRoute::findOrFail($id)->delete();
        return back()->with('success', 'Route berhasil dihapus');
    }

    // --- LOGIC PERMISSION ---
    public function storePermission(Request $request) {
        Permission::create($request->all());
        return back()->with('success', 'Permission berhasil dibuat');
    }

    public function updatePermissionMatrix(Request $request)
    {
        // Validasi data
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
            'active' => 'required|boolean'
        ]);

        $role = Role::findOrFail($request->role_id);

        // Logic update permission
        if ($request->active) {
            // Jika dicentang -> Berikan permission
            $role->permissions()->attach($request->permission_id);
            $message = 'Permission diberikan.';
        } else {
            // Jika tidak dicentang -> Hapus permission
            $role->permissions()->detach($request->permission_id);
            $message = 'Permission dicabut.';
        }

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function saveMatrix(Request $request)
    {
        // 1. Ambil input matrix dari form
        $matrix = $request->input('matrix', []);

        // 2. Ambil semua role
        $roles = \App\Models\Role::all();

        // 3. Loop setiap role
        foreach ($roles as $role) {
            // Cek apakah ada data permission yang dikirim untuk role ini?
            if (isset($matrix[$role->id])) {

                // A. UPDATE PERMISSIONS (Tabel role_has_permissions)
                // Sync data permission sesuai checkbox
                $role->permissions()->sync($matrix[$role->id]);

                // ==========================================================
                // B. AUTO-UPDATE MENU (Tabel role_has_menus) - LOGIKA BARU
                // ==========================================================

                // 1. Cari Permission yang baru saja dicentang/disimpan
                // 2. Ambil 'menu_id' dari permission tersebut
                // 3. Pastikan unik (karena 1 menu bisa punya 4 permission, kita cuma butuh ID menunya sekali)

                $menuIds = \App\Models\Permission::whereIn('id', $matrix[$role->id])
                            ->pluck('menu_id') // Ambil kolom menu_id
                            ->unique()         // Hapus duplikat
                            ->filter()         // Hapus yang null/kosong
                            ->toArray();

                // 4. Sync Menu ke Role (Otomatis Menu muncul/hilang sesuai Permission)
                $role->menus()->sync($menuIds);

            } else {
                // Jika tidak ada centangan sama sekali:
                // Cabut SEMUA permission DAN SEMUA menu dari role tersebut
                $role->permissions()->detach();
                $role->menus()->detach();
            }
        }

        return back()->with('success', 'Matrix Izin Akses & Menu Sidebar berhasil diperbarui!');
    }

        // App\Http\Controllers\MenuController.php

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:menu,id',
        ]);

        foreach ($request->ids as $index => $id) {
            // Update urutan
            // Asumsi nama model Anda 'Menu'
            \App\Models\Menu::where('id', $id)->update([
                'order' => $index + 1
            ]);
        }

        // === BAGIAN PENTING ===
        // Simpan pesan ke session agar muncul di alert hijau blade setelah reload
        // return back()->with('success', 'Urutan menu berhasil diperbarui!');
        session()->flash('success', 'Urutan menu berhasil diperbarui!');

        return response()->json(['success' => true]);
    }
}
