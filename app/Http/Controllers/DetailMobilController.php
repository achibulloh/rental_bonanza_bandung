<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class DetailMobilController extends Controller
{
    public function index($id)
    {
        // PERBAIKAN: Hapus 'images' dari array with()
        // Karena images adalah kolom di tabel cars, dia otomatis ikut terambil.
        $mobil = Car::with(['brand', 'type', 'packages'])->findOrFail($id);

        return view('dashboard.detail_mobil.index', compact('mobil'));
    }
}
