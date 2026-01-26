<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function index()
    {
        $documents = UserDocument::with('user')
            ->whereIn('document_type', ['KTP', 'SIM', 'NPWP', 'KTM', 'KTA', 'SELFIE_KTP_SIM'])
            ->whereNotNull('file_path')
            // LOGIC: Hanya ambil Pending dan Rejected
            ->whereIn('status', ['pending', 'rejected'])
            ->orderBy('updated_at', 'ASC') // Urutkan dari yang paling lama menunggu
            ->get();

        return view('dashboard.verification.index', compact('documents'));
    }

    public function approve($id)
    {
        $document = UserDocument::findOrFail($id);
        $document->status = 'verified';
        $document->rejection_reason = null;
        $document->verified_by = Auth::id();
        $document->updated_at = now();
        $document->save();

        return redirect()->back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:255']);

        $document = UserDocument::findOrFail($id);
        $document->status = 'rejected';
        $document->rejection_reason = $request->rejection_reason;
        $document->verified_by = Auth::id();
        $document->updated_at = now();
        $document->save();

        return redirect()->back()->with('success', 'Dokumen ditolak.');
    }
}
