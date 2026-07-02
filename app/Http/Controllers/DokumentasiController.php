<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanDokumen;
use Illuminate\Http\Request;

class DokumentasiController extends Controller
{
    public function index()
    {
        // Load kegiatans for the global upload dropdown
        $kegiatans = Kegiatan::with('desa')->orderBy('nama_kegiatan')->get();
        return view('modules.dokumentasi', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'tipe' => 'required|in:sebelum,proses,sesudah,lainnya',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // max 10MB
            'caption' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('dokumentasi', 'public');

        KegiatanDokumen::create([
            'kegiatan_id' => $request->kegiatan_id,
            'user_id' => auth()->id(),
            'tipe' => $request->tipe,
            'file_path' => $path,
            'caption' => $request->caption,
            // Assuming no automatic GPS extraction for now, can be added later
            'taken_at' => now(),
        ]);

        return back()->with('success', 'Dokumen/Foto berhasil diunggah.');
    }
}
