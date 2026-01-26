<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // --- 1. FUNGSI FORMAT HP (SANGAT PENTING) ---
    // Fungsi ini memastikan nomor selalu berawalan 628...
    // Contoh: 0812 -> 62812, +62812 -> 62812, 812 -> 62812
    private function formatPhone($phone)
    {
        // 1. Hapus semua karakter selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // 2. Cek awalan
        if (substr($phone, 0, 2) === '08') {
            return '62' . substr($phone, 1);
        }
        elseif (substr($phone, 0, 3) === '628') {
            return $phone; // Sudah benar
        }
        elseif (substr($phone, 0, 1) === '8') {
            return '62' . $phone;
        }

        return $phone; // Kembalikan apa adanya jika format lain
    }

    // Ambil Settingan WA
    private function getWaSettings()
    {
        return DB::table('settings')
                ->whereIn('key', ['wa_gateway', 'wa_user_code', 'wa_device_id', 'wa_secret'])
                ->pluck('value', 'key');
    }

    // --- VIEW AUTH ---
    public function login(){ return view("auth.login"); }
    public function register(){ return view("auth.register"); }

    // --- 2. PROSES REGISTER ---
    public function auth_prosses_register(Request $request){
        // 1. Validasi Input
        $validatedData = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'     => ['required', 'numeric', 'digits_between:10,15'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'terms'     => ['accepted'],
        ]);

        try {
            $formattedPhone = $this->formatPhone($validatedData['phone']);

            // 2. Cek Config WA Dulu Sebelum Buat User
            $waConfig = $this->getWaSettings();

            // Jika Config Kosong/Gateway Mati, langsung tolak (opsional)
            if (!isset($waConfig['wa_gateway']) || $waConfig['wa_gateway'] != '1') {
                toastr()->error('Sistem Registrasi sedang sibuk/maintenance.', 'Maaf');
                return back()->withInput();
            }

            // 3. Simpan User (Sementara)
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $formattedPhone,
                'password' => Hash::make($validatedData['password']),
                'role_id' => 4,
                'is_active' => 0
            ]);

            // 4. Request Generate OTP
            $response = Http::post('https://api.kirimi.id/v1/generate-otp', [
                'user_code' => $waConfig['wa_user_code'],
                'device_id' => $waConfig['wa_device_id'],
                'phone'     => $formattedPhone,
                'secret'    => $waConfig['wa_secret']
            ]);

            $result = $response->json();

            // 5. Cek Berhasil atau Gagal
            if ($response->successful() && isset($result['success']) && $result['success'] == true) {
                // BERHASIL: Lanjut ke Halaman Verifikasi
                Session::put('verification_phone', $formattedPhone);
                toastr()->success('Kode OTP terkirim ke WhatsApp!', 'Berhasil');
                return redirect()->route('otp.verify');
            } else {
                // GAGAL (Mungkin Limit Habis, Device Mati, dll)

                // Ambil pesan error dari API (Contoh: "Customer ini sudah meminta OTP...")
                $pesanError = $result['message'] ?? 'Gagal mengirim OTP. Coba lagi nanti.';

                // Hapus user agar bisa daftar ulang nanti
                $user->delete();

                // Tampilkan notifikasi error ke user
                toastr()->error($pesanError, 'Gagal');
                return back()->withInput();
            }

        } catch (\Exception $e) {
            // Hapus user jika error sistem
            if(isset($user)) $user->delete();

            toastr()->error('Terjadi kesalahan sistem.', 'Error');
            return back()->withInput();
        }
    }

    // --- 3. LOGIN (CEK AKTIF) ---
    public function auth_prosses_login(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            // CEK APAKAH SUDAH AKTIF
            if ($user->is_active == 0) {
                // Jika belum aktif, kirim ulang OTP
                $this->resendOtpForLogin($user);
                return redirect()->route('otp.verify');
            }

            if (Auth::attempt($validate)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }
        }

        toastr()->error('Email atau password salah.');
        return back()->onlyInput('email');
    }

    // Fungsi bantuan kirim ulang saat login
    private function resendOtpForLogin($user) {
        $waConfig = $this->getWaSettings();
        if (isset($waConfig['wa_gateway']) && $waConfig['wa_gateway'] == '1') {
            // Pastikan format nomor HP user benar
            $phone = $this->formatPhone($user->phone);

            Http::post('https://api.kirimi.id/v1/generate-otp', [
                'user_code' => $waConfig['wa_user_code'],
                'device_id' => $waConfig['wa_device_id'],
                'phone'     => $phone,
                'secret'    => $waConfig['wa_secret']
            ]);

            Session::put('verification_phone', $phone);
            toastr()->warning('Akun belum aktif. Kode OTP baru dikirim.', 'Verifikasi');
        }
    }

    // --- 4. TAMPILAN VERIFIKASI ---
    public function showVerifyOtp()
    {
        if (!Session::has('verification_phone')) {
            return redirect()->route('login')->with('error', 'Sesi habis.');
        }
        return view('auth.verify-otp');
    }

    // --- 5. PROSES VALIDASI OTP (FIXED) ---
    public function processVerifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        // Ambil HP dari session dan FORMAT ULANG (PENTING!)
        $sessionPhone = Session::get('verification_phone');
        $phone = $this->formatPhone($sessionPhone);

        // Cari User (Gunakan phone yang sudah diformat)
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            toastr()->error('User tidak ditemukan. HP: ' . $phone, 'Error');
            return back();
        }

        $waConfig = $this->getWaSettings();

        try {
            // KIRIM VALIDASI KE API
            // Pastikan 'phone' sama persis dengan saat register
            $response = Http::post('https://api.kirimi.id/v1/validate-otp', [
                'user_code' => $waConfig['wa_user_code'],
                'device_id' => $waConfig['wa_device_id'],
                'phone'     => $phone, // Format 62...
                'otp'       => (string) $request->otp, // Pastikan string
                'secret'    => $waConfig['wa_secret']
            ]);

            $result = $response->json();

            // Cek sukses
            $isSuccess = false;
            if ($response->successful()) {
                if ((isset($result['success']) && $result['success'] == true) ||
                    (isset($result['data']['verified']) && $result['data']['verified'] == true)) {
                    $isSuccess = true;
                }
            }

            if ($isSuccess) {
                // UPDATE KE DB
                $user->update(['is_active' => 1]);

                Session::forget('verification_phone');
                Auth::login($user);

                toastr()->success('Verifikasi Berhasil!', 'Sukses');
                return redirect()->intended('dashboard');
            } else {
                $msg = $result['message'] ?? 'Kode OTP salah.';
                toastr()->error($msg, 'Gagal');
                return back();
            }

        } catch (\Exception $e) {
            toastr()->error('Koneksi Error.', 'Error');
            return back();
        }
    }

    // --- 6. KIRIM ULANG (RESEND) ---
    public function resendOtp()
    {
        if (!Session::has('verification_phone')) {
            return redirect()->route('login');
        }

        $phone = $this->formatPhone(Session::get('verification_phone'));
        $waConfig = $this->getWaSettings();

        try {
            $response = Http::post('https://api.kirimi.id/v1/generate-otp', [
                'user_code' => $waConfig['wa_user_code'],
                'device_id' => $waConfig['wa_device_id'],
                'phone'     => $phone,
                'secret'    => $waConfig['wa_secret']
            ]);

            if ($response->successful()) {
                toastr()->success('Kode OTP baru terkirim.', 'Sukses');
            } else {
                toastr()->error('Gagal kirim ulang.', 'Gagal');
            }
            return back();

        } catch (\Exception $e) {
            return back()->with('error', 'Error koneksi.');
        }
    }
}
