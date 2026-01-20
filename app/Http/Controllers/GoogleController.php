<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // 1. Redirect KHUSUS LOGIN (Set session 'login')
    public function loginToGoogle()
    {
        // Simpan tanda bahwa user ingin 'login'
        session(['google_action' => 'login']);
        return Socialite::driver('google')->redirect();
    }

    // 2. Redirect KHUSUS REGISTER (Set session 'register')
    public function registerToGoogle()
    {
        // Simpan tanda bahwa user ingin 'register'
        session(['google_action' => 'register']);
        return Socialite::driver('google')->redirect();
    }

    // 3. Callback (Menangani keduanya)
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Ambil "niat" user tadi (login atau register?)
            $action = session('google_action');

            // Cari user di database
            $findUser = User::where('google_id', $googleUser->id)->first();

            // Jika tidak ketemu via Google ID, cari via Email
            if (!$findUser) {
                $findUser = User::where('email', $googleUser->email)->first();
                // Jika ketemu via email, update Google ID-nya sekalian (Link Account)
                if ($findUser) {
                    $findUser->update(['google_id' => $googleUser->id]);
                }
            }

            // === LOGIKA PEMISAH ===

            // KASUS A: NIATNYA LOGIN
            if ($action == 'login') {
                if ($findUser) {
                    // User ditemukan -> Izinkan Login
                    Auth::login($findUser);
                    return redirect()->intended('dashboard');
                } else {
                    // User TIDAK ditemukan -> TOLAK (Jangan Create)
                    return redirect()->route('login')
                        ->with('error', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
                }
            }

            // KASUS B: NIATNYA REGISTER
            else {
                if ($findUser) {
                    // Ternyata user sudah ada -> Login saja (Jangan error)
                    Auth::login($findUser);
                    return redirect()->intended('dashboard');
                } else {
                    // User belum ada -> BOLEH CREATE
                    $newUser = User::create([
                        'name'      => $googleUser->name,
                        'email'     => $googleUser->email,
                        'phone'     => null,
                        'google_id' => $googleUser->id,
                        'password'  => bcrypt(Str::random(16)),
                        'role_id'   => 4
                    ]);

                    Auth::login($newUser);
                    return redirect()->intended('dashboard')->with('success', 'Registrasi berhasil! Silakan lengkapi profil.');
                }
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
