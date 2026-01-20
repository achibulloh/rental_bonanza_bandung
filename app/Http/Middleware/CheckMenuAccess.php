<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 1. Jika user admin, bypass semua (Opsional)
        if ($user->role && $user->role->name === 'admin') {
            return $next($request);
        }

        // 2. Ambil URL saat ini (contoh: /manajemen-user)
        $currentPath = '/' . $request->path();

        // 3. Cek apakah URL ini ada di tabel menu database
        $menu = Menu::where('url', $currentPath)->first();

        // Jika URL bukan bagian dari menu yang diatur (misal route API atau logout), loloskan saja
        if (!$menu) {
            return $next($request);
        }

        // 4. Cek apakah Role user memiliki akses ke menu ini
        if ($user->role && $user->role->menus()->where('menu.id', $menu->id)->exists()) {
            return $next($request);
        }

        // 5. Jika tidak punya akses, lempar 403 Forbidden
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
