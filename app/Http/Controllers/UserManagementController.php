<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role; // Pastikan Model Role ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan relasi role
        // Menggunakan latest() agar data baru muncul di atas
        $users = User::with('role')->latest()->get();

        // Ambil semua role untuk dropdown di modal
        $roles = Role::all();

        return view('dashboard.manajemen_user.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|min:6|confirmed', // Pastikan ada input password_confirmation di view
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
        ]);

        // Simpan User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id, // Asumsi relasi langsung (1 user 1 role)
            'is_active' => $request->is_active,
        ]);

        // Jika menggunakan Spatie Permission (Opsional/Alternatif)
        // $user->assignRole($request->role_id);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
            'password' => 'nullable|min:6|confirmed', // Password opsional saat edit
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah hapus diri sendiri
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}
