<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage; // Pastikan Model ContactMessage tetap ada
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ReplyMessageController extends Controller
{
    // 1. HALAMAN DAFTAR PESAN (INDEX)
    public function index()
    {
        // Logika: Pending di atas, lalu urut waktu terbaru
        $messages = ContactMessage::with('user')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
            ->latest()
            ->get();
        // $messages = ContactMessage::with('user')
        //     ->orderBy('status', 'asc')
        //     ->latest()
        //     ->paginate(10);

        // SESUAI PERMINTAAN: View ke folder 'dashboard.balas_pesan.index'
        return view('dashboard.balas_pesan.index', compact('messages'));
    }

    // 2. KIRIM BALASAN (POST)
    public function sendReply(Request $request, $id)
    {
        $request->validate(['reply_message' => 'required|string']);

        $contact = ContactMessage::with('user')->findOrFail($id);

        $contact->update([
            'reply_message' => $request->reply_message,
            'replied_at' => now(),
            'status' => 'replied',
            'replied_by' => Auth::id()
        ]);

        // Kirim Email
        if ($contact->user && $contact->user->email) {
            try {
                $data = [
                    'name' => $contact->user->name,
                    'user_subject' => $contact->subject,
                    'user_message' => $contact->message,
                    'reply' => $request->reply_message
                ];

                Mail::send('emails.contact_reply', $data, function($mail) use ($contact) {
                    $mail->to($contact->user->email)
                         ->subject('Balasan: ' . $contact->subject);
                });
            } catch (\Exception $e) {
                return redirect()->back()->with('warning', 'Balasan disimpan, tapi Email gagal terkirim.');
            }
        }

        return redirect()->back()->with('success', 'Pesan berhasil dibalas.');
    }
}
