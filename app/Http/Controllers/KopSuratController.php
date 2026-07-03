<?php

namespace App\Http\Controllers;

use App\Models\KopSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KopSuratController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        
        // Kabupaten doesn't need kop surat in this context, only Kecamatan/Desa
        if ($user->isKabupaten()) {
            abort(403, 'Kabupaten tidak memiliki Kop Surat khusus.');
        }

        $entity = $user->isKecamatan() ? $user->kecamatan : $user->desa;
        $kopSurat = $entity->kopSurat;

        return view('settings.kop_surat', compact('kopSurat'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isKabupaten()) {
            abort(403, 'Unauthorized.');
        }

        $entity = $user->isKecamatan() ? $user->kecamatan : $user->desa;

        $validated = $request->validate([
            'pemerintah' => 'nullable|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        $kopSurat = $entity->kopSurat()->firstOrNew([]);
        $kopSurat->pemerintah = $validated['pemerintah'] ?? null;
        $kopSurat->instansi = $validated['instansi'] ?? null;
        $kopSurat->alamat = $validated['alamat'] ?? null;
        $kopSurat->kontak = $validated['kontak'] ?? null;

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($kopSurat->logo_path && Storage::disk('public')->exists($kopSurat->logo_path)) {
                Storage::disk('public')->delete($kopSurat->logo_path);
            }
            $path = $request->file('logo')->store('kop-surat', 'public');
            $kopSurat->logo_path = $path;
        }

        $kopSurat->save();

        return redirect()->route('settings.kop-surat')->with('success', 'Pengaturan Kop Surat berhasil disimpan.');
    }
}
