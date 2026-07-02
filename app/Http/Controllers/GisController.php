<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;

class GisController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base query for mapping activities
        $query = Kegiatan::with('desa.kecamatan')
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude');

        // Tenant filtering based on user role
        if ($user->isKecamatan()) {
            $desaIds = Desa::where('kecamatan_id', $user->kecamatan_id)->pluck('id');
            $query->whereIn('desa_id', $desaIds);
        } elseif ($user->isDesa()) {
            $query->where('desa_id', $user->desa_id);
        }

        // Apply explicit filters if requested
        if ($request->filled('kecamatan_id')) {
            $desaIds = Desa::where('kecamatan_id', $request->kecamatan_id)->pluck('id');
            $query->whereIn('desa_id', $desaIds);
        }
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kegiatans = $query->get();

        // Format data for Leaflet JS
        $mapData = $kegiatans->map(function ($k) {
            return [
                'id' => $k->id,
                'nama' => $k->nama_kegiatan,
                'desa' => $k->desa->nama,
                'kecamatan' => $k->desa->kecamatan->nama,
                'status' => $k->status,
                'progres' => $k->progres_fisik,
                'lat' => $k->latitude,
                'lng' => $k->longitude,
                'url' => route('kegiatan.show', $k->id)
            ];
        });

        $kecamatans = Kecamatan::orderBy('nama')->get();
        $desas = collect();
        if ($request->filled('kecamatan_id')) {
            $desas = Desa::where('kecamatan_id', $request->kecamatan_id)->orderBy('nama')->get();
        } elseif ($user->isKecamatan()) {
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama')->get();
        }

        return view('gis.index', compact('mapData', 'kecamatans', 'desas'));
    }
}
