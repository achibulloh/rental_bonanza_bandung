<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class WhatsappService
{
    // 1. KIRIM PESAN TEKS BIASA (Untuk Notifikasi Approval)
    public function sendMessage($targetPhone, $message)
    {
        // Ambil Config
        $config = Setting::whereIn('key', ['wa_gateway', 'wa_user_code', 'wa_device_id', 'wa_secret'])->pluck('value', 'key');

        // Cek Status Gateway
        if (($config['wa_gateway'] ?? '0') != '1') {
            return ['status' => false, 'message' => 'WA Gateway OFF.'];
        }

        // Format Nomor (08xx -> 628xx)
        $receiver = $this->formatPhone($targetPhone);

        // Payload Kirimi.id
        $payload = [
            'user_code' => $config['wa_user_code'] ?? '',
            'device_id' => $config['wa_device_id'] ?? '',
            'secret'    => $config['wa_secret'] ?? '',
            'receiver'  => $receiver,
            'message'   => $message,
            'enableTypingEffect' => false
        ];

        try {
            $response = Http::timeout(30)->post('https://api.kirimi.id/v1/send-message', $payload);
            return $response->json();
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    // 2. KIRIM DOKUMEN (Function Lama Anda)
    public function sendDocument($targetPhone, $fileUrl, $caption = '')
    {
        $fileUrl = str_replace('http://', 'https://', $fileUrl);

        $config = Setting::whereIn('key', ['wa_gateway', 'wa_user_code', 'wa_device_id', 'wa_secret'])->pluck('value', 'key');

        if (($config['wa_gateway'] ?? '0') != '1') {
            return ['status' => false, 'message' => 'WA Gateway OFF.'];
        }

        $receiver = $this->formatPhone($targetPhone);

        $payload = [
            'user_code' => $config['wa_user_code'] ?? '',
            'device_id' => $config['wa_device_id'] ?? '',
            'secret'    => $config['wa_secret'] ?? '',
            'receiver'  => $receiver,
            'message'   => $caption,
            'media_url' => $fileUrl,
            'fileName'  => 'Bukti_Transaksi.pdf', // Bisa dibuat dinamis jika perlu
            'enableTypingEffect' => false
        ];

        try {
            $response = Http::timeout(60)->post('https://api.kirimi.id/v1/send-message', $payload);
            return $response->json();
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    // Helper Format Nomor
    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) == '0') $phone = '62' . substr($phone, 1);
        if (substr($phone, 0, 1) == '8') $phone = '62' . $phone;
        return $phone;
    }
}
