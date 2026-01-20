<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    public function index()
    {
        // Ambil settings seperti biasa
        $settings = Setting::pluck('value', 'key')->toArray();

        // Ambil pesan TERAKHIR user ini
        $lastMessage = ContactMessage::where('user_id', Auth::id())
                        ->latest() // Urutkan dari yang terbaru
                        ->first();

        return view('dashboard.bantuan.index', compact('settings', 'lastMessage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        $data = [
            'user_id' => Auth::id(), // ID User yang sedang login
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => 'pending',
        ];

        // Upload Foto ke folder storage/app/public/contact-images
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('contact-images', 'public');
            $data['image_path'] = $path;
        }

        ContactMessage::create($data);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim! ID tiket Anda telah dibuat.');
    }
}
