<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // ==========================================
    // TAHAP 1: REQUEST OTP (Halaman Index)
    // ==========================================

    public function showRequestForm()
    {
        // 1. CEK OTOMATIS: Jika user sudah punya sesi OTP, langsung ke halaman Verifikasi
        if (Session::has('reset_phone')) {
            return redirect()->route('password.verify');
        }

        // 2. CEK OTOMATIS: Jika user sudah selesai verifikasi, langsung ke Ganti Password
        if (Session::has('allow_reset_password')) {
            return redirect()->route('password.reset.form');
        }

        return view('auth.forgotpassword.index');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required']);

        // 1. Format & Cari User
        $input = $request->email;
        $phone = $this->formatPhone($input);
        $user = User::where('email', $input)->orWhere('phone', $phone)->first();

        if (!$user) {
            toastr()->error('Akun tidak ditemukan.', 'Gagal');
            return back()->withInput();
        }

        // 2. Simpan Nomor ke Session
        $targetPhone = $this->formatPhone($user->phone);

        // 3. Ambil Config & Request API
        $waConfig = $this->getWaSettings();

        if (!isset($waConfig['wa_gateway']) || $waConfig['wa_gateway'] != '1') {
            toastr()->error('Sistem OTP sedang maintenance.', 'Maaf');
            return back();
        }

        try {
            $response = Http::post('https://api.kirimi.id/v1/generate-otp', [
                'user_code' => $waConfig['wa_user_code'],
                'device_id' => $waConfig['wa_device_id'],
                'phone'     => $targetPhone,
                'secret'    => $waConfig['wa_secret']
            ]);

            $result = $response->json();

            // 4. LOGIKA SUKSES/GAGAL YANG LEBIH TEGAS
            // Kita gunakan variabel flag biar rapi
            $isSent = false;

            if ($response->successful()) {
                if ((isset($result['success']) && $result['success'] == true) ||
                    (isset($result['status']) && $result['status'] == true)) {
                    $isSent = true;
                }
            }

            if ($isSent) {
                // === SUKSES ===
                // Simpan sesi DULU
                Session::put('reset_phone', $targetPhone);

                // Tampilkan notifikasi
                toastr()->success('Kode OTP telah dikirim ke WhatsApp.', 'Berhasil');

                // LANGSUNG REDIRECT (Jangan ada kode lain setelah ini)
                return redirect()->route('password.verify');

            } else {
                // === GAGAL API ===
                $msg = $result['message'] ?? 'Gagal mengirim OTP.';
                toastr()->error($msg, 'Gagal');
                return back()->withInput();
            }

        } catch (\Exception $e) {
            toastr()->error('Terjadi kesalahan koneksi server.', 'Error');
            return back()->withInput();
        }
    }

    // ==========================================
    // TAHAP 2: VERIFIKASI KODE OTP
    // ==========================================

    public function showVerifyForm()
    {
        // 1. CEK OTOMATIS: Jika user sudah verifikasi, langsung ke Ganti Password
        if (Session::has('allow_reset_password')) {
            return redirect()->route('password.reset.form');
        }

        // 2. CEK OTOMATIS: Jika user BELUM punya sesi telepon, tendang ke awal
        if (!Session::has('reset_phone')) {
            return redirect()->route('password.request');
        }

        return view('auth.forgotpassword.verify');
    }

    public function processVerify(Request $request)
    {
        $request->validate(['otp' => 'required']);

        $phone = Session::get('reset_phone');
        $waConfig = $this->getWaSettings();

        try {
            $response = Http::post('https://api.kirimi.id/v1/validate-otp', [
                'user_code' => $waConfig['wa_user_code'],
                'device_id' => $waConfig['wa_device_id'],
                'phone'     => $phone,
                'otp'       => (string) $request->otp,
                'secret'    => $waConfig['wa_secret']
            ]);

            $result = $response->json();

            $isValid = false;
            if ($response->successful()) {
                if ((isset($result['success']) && $result['success'] == true) ||
                    (isset($result['data']['verified']) && $result['data']['verified'] == true)) {
                    $isValid = true;
                }
            }

            if ($isValid) {
                // === OTP BENAR ===
                // Berikan tiket masuk ke halaman ganti password
                Session::put('allow_reset_password', true);

                toastr()->success('Verifikasi Berhasil! Silakan buat password baru.', 'Sukses');

                // LANGSUNG REDIRECT
                return redirect()->route('password.reset.form');

            } else {
                // === OTP SALAH ===
                $msg = $result['message'] ?? 'Kode OTP Salah / Kadaluarsa.';
                toastr()->error($msg, 'Gagal');
                return back();
            }

        } catch (\Exception $e) {
            toastr()->error('Error Server.', 'Error');
            return back();
        }
    }

    // ==========================================
    // TAHAP 3: GANTI PASSWORD BARU
    // ==========================================

    public function showChangeForm()
    {
        // 1. CEK OTOMATIS: User WAJIB punya tiket 'allow_reset_password'
        if (!Session::has('allow_reset_password')) {
            toastr()->error('Akses ditolak. Silakan verifikasi OTP dulu.', 'Error');
            return redirect()->route('password.request');
        }

        return view('auth.forgotpassword.change');
    }

    public function processChange(Request $request)
    {
        if (!Session::has('allow_reset_password')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $phone = Session::get('reset_phone');
        $user = User::where('phone', $phone)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // BERSIHKAN SEMUA SESSION
            Session::forget(['reset_phone', 'allow_reset_password']);

            toastr()->success('Password berhasil diubah. Silakan Login.', 'Selesai');
            return redirect()->route('login');
        }

        return back()->with('error', 'Terjadi kesalahan data user.');
    }

    // ==========================================
    // HELPER (TAMBAHAN: RESET SESSION)
    // ==========================================

    // PENTING: Tambahkan route ini di web.php jika ingin tombol "Bukan nomor ini?"
    // Route::get('/lupa-password/reset', [ForgotPasswordController::class, 'resetSession'])->name('password.reset.session');

    public function resetSession()
    {
        Session::forget(['reset_phone', 'allow_reset_password']);
        return redirect()->route('password.request');
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 2) === '08') return '62' . substr($phone, 1);
        if (substr($phone, 0, 1) === '8') return '62' . $phone;
        return $phone;
    }

    private function getWaSettings()
    {
        return DB::table('settings')
                ->whereIn('key', ['wa_gateway', 'wa_user_code', 'wa_device_id', 'wa_secret'])
                ->pluck('value', 'key');
    }
}
