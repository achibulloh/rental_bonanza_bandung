<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserDocument;

class ProfileController extends Controller
{
    /**
     * Menampilkan Halaman Profil berdasarkan ID
     */
    public function index($id)
    {
        // KEAMANAN: Pastikan user hanya bisa melihat profilnya sendiri
        if ($id != Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses profil ini.');
        }

        $user = User::findOrFail($id);

        // Ambil dokumen user
        $documents = $user->documents()->latest()->get();

        return view('dashboard.myprofile.index', compact('user', 'documents'));
    }

    /**
     * Update Data Diri (Sesuai Tabel Users)
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id, // Validasi Unique untuk Phone
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female', // Validasi Gender (Enum)
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data inputan termasuk gender
        $data = $request->only(['name', 'email', 'phone', 'birth_date', 'gender', 'address']);

        // Handle Upload Foto Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        User::where('id', Auth::id())->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Upload Dokumen
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:KTP,SIM,NPWP,KTM,KTA,SELFIE_KTP_SIM',
            'document_number' => 'nullable|string|max:50',
            'document_file' => 'required|image|mimes:jpeg,png,jpg|max:6048',
        ]);

        $user = Auth::user();
        $path = $request->file('document_file')->store('documents', 'public');

        $existingDoc = UserDocument::where('user_id', $user->id)
                        ->where('document_type', $request->document_type)
                        ->first();

        if ($existingDoc) {
            if (Storage::exists('public/' . $existingDoc->file_path)) {
                Storage::delete('public/' . $existingDoc->file_path);
            }
            $existingDoc->update([
                'document_number' => $request->document_number,
                'file_path' => $path,
                'status' => 'pending',
                'verified_by' => null,
                'rejection_reason' => null
            ]);
            $message = 'Dokumen berhasil diperbaiki dan dikirim ulang.';
        } else {
            UserDocument::create([
                'user_id' => $user->id,
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'file_path' => $path,
                'status' => 'pending',
                'verified_by' => null,
                'rejection_reason' => null
            ]);
            $message = 'Dokumen berhasil diunggah.';
        }

        return back()->with('success', $message);
    }
}
