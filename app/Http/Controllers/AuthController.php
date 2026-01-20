<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Models\User;

class AuthController extends Controller
{
    // Login
    public function login(){
        return view("auth.login");
    }

    // Prosses Auth Login
    public function auth_prosses_login(Request $request){// 1. Validasi Input (Security: Input Sanitization)
        $validate = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek Login
        // Auth::attempt otomatis melakukan hashing password verification (Security)
        if (Auth::attempt($validate)) {

            // 3. Regenerasi Session (Security: Mencegah Session Fixation)
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        // ERROR TOAST
        // Jika login gagal
        toastr()->error('An error has occurred please try again later.', 'Login Gagal');

        // Kembalikan ke halaman login dengan error pada input
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Register
    public function register(){
        return view("auth.register");
    }

    // Prosses Auth Register
    public function auth_prosses_register(Request $request){
        // 1. Validasi Input (Security Layer 1)
        $validatedData = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'], // Unique mencegah duplikasi
            'phone'     => ['required', 'numeric', 'digits_between:10,15'], // Validasi nomor hp
            'password'  => ['required', 'confirmed', Rules\Password::defaults()], // Confirmed mencocokkan dengan password_confirmation
            'terms'     => ['accepted'], // Wajib dicentang
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique'  => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui syarat & ketentuan.'
        ]);

        try {
            // 2. Simpan User ke Database (Security Layer 2: Hashing)
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'password' => Hash::make($validatedData['password']), // Hash password!
                'role_id' => 4,
            ]);

            // Opsional: Langsung login setelah daftar
            // Auth::login($user);

            // 3. Notifikasi Sukses dengan Toastr
            toastr()->success('Akun berhasil dibuat! Silakan login.', 'Registrasi Berhasil');

            // Redirect ke halaman login
            return redirect()->route('login');

        } catch (\Exception $e) {
            // Jika terjadi error database dsb
            toastr()->error('Terjadi kesalahan saat mendaftar. Coba lagi.', 'Error');
            return back()->withInput();
        }
    }
}
