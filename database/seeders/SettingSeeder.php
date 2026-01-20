<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Identitas
            'app_name' => 'Bonanza Rental Mobil Bandung',
            'app_tagline' => 'Solusi Sewa Mobil Terbaik Di Bandung',
            'app_desc' => 'Solusi rental mobil terpercaya dengan layanan profesional dan armada berkualitas untuk perjalanan Anda.',

            // Kontak
            'address' => 'Jl. Murtad No. 22, Malabar,Kec.Lengkong, Kota Bandung,Jawa Barat, 40262',
            'phone' => '+62 889-9998-8909',
            'whatsapp' => '6288999988909',
            'email' => 'info@bonanzarentalmobilbandung.com',
            'office_hours' => 'Senin - Minggu: 08:00 - 20:00',
            'maps_link' => '',

            // Payment
            'payment_method' => 'transfer',
            'merchantid' => 'G831298594',
            'clientkey' => 'SB-Mid-client-d3H1Lk8igD8s9R5S',
            'serverkey' => 'SB-Mid-server-oowA8Rp9HgXLlFbybWucxHSm',
            'isProduction' => 'false',
            'namabank' => 'Bank BCA',
            'namarekening' => 'Bonanza Bintang Perkasa',
            'nomorrekening' => '7772910005',

            // WA Gateway
            'wa_gateway' => '1',
            'wa_user_code' => 'KMPN8S126',
            'wa_device_id' => 'D-M4Q6L',
            'wa_secret' => '27ff18265f0735f7f77f2a5e64130562c84bc47c7fba169c172e23668126347e',

            // Social
            'social_ig' => 'https://instagram.com/bonanzarentcarbdg',
            'social_fb' => 'https://www.facebook.com/share/1FqdoifSAd/',
            'social_tiktok' => 'https://www.tiktok.com/@pt.bonanzabintangperkasa',

            // Analytic
            'GoogleSchConsol' => 'hWZ6T9RzE4xbnGQNGBvoxoXGPjsEk-MpXylsYV-G2A8',
            'GoogleTag' => 'GTM-M6FSRMFR',

            // Images (Path default null)
            'logo_web' => 'settings/IvW1cfDxczM0ZoxkBMvPy8qvXlQYggvbDQHGTm5o.png',
            'favicon' => null,
        ];

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
