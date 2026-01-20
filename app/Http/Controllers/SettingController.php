<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil semua setting dan ubah menjadi array [key => value]
        // Agar di view bisa dipanggil seperti $settings['app_name']
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('dashboard.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // 1. Ambil semua input kecuali token dan file
        $inputs = $request->except(['_token', 'logo_web', 'favicon']);

        // 2. Loop dan simpan text inputs
        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 3. Handle Upload Logo
        if ($request->hasFile('logo_web')) {
            $request->validate(['logo_web' => 'image|mimes:jpeg,png,jpg,webp|max:2048']);

            // Hapus logo lama jika ada
            $oldLogo = Setting::get('logo_web');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Upload baru
            $path = $request->file('logo_web')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'logo_web'], ['value' => $path]);
        }

        // 4. Handle Upload Favicon
        if ($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'image|mimes:ico,png,jpg|max:1024']);

            $oldFav = Setting::get('favicon');
            if ($oldFav && Storage::disk('public')->exists($oldFav)) {
                Storage::disk('public')->delete($oldFav);
            }

            $path = $request->file('favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => $path]);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
