<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class TransparansiController extends Controller
{
    public function index(Request $request)
    {
        $kecamatans = Kecamatan::withCount('desas')->get();
        
        $tahunList = Kegiatan::select('tahun_anggaran')
            ->distinct()
            ->orderByDesc('tahun_anggaran')
            ->pluck('tahun_anggaran');

        $selectedKecId = $request->get('kecamatan_id');
        $selectedDesaId = $request->get('desa_id');
        $selectedTahun = $request->get('tahun_anggaran');
        $selectedSemester = $request->get('semester');
        $search = $request->get('search');

        $desas = collect();
        if ($selectedKecId) {
            $desas = Desa::where('kecamatan_id', $selectedKecId)->orderBy('nama')->get();
        }

        $query = Kegiatan::with(['desa.kecamatan', 'sumberDana']);

        if ($selectedKecId) {
            $query->whereHas('desa', function($q) use ($selectedKecId) {
                $q->where('kecamatan_id', $selectedKecId);
            });
        }
        
        if ($selectedDesaId) {
            $query->where('desa_id', $selectedDesaId);
        }

        if ($selectedTahun) {
            $query->where('tahun_anggaran', $selectedTahun);
        }

        if ($selectedSemester) {
            $query->where('periode_anggaran', $selectedSemester);
        }

        if ($search) {
            $query->where('nama_kegiatan', 'like', "%{$search}%");
        }

        $kegiatans = $query->orderBy('created_at', 'desc')->paginate(12);
        $kegiatans->appends($request->all());

        // Stats for selected filters
        $statsQuery = Kegiatan::query();
        
        if ($selectedKecId) {
            $statsQuery->whereHas('desa', function($q) use ($selectedKecId) {
                $q->where('kecamatan_id', $selectedKecId);
            });
        }
        if ($selectedDesaId) {
            $statsQuery->where('desa_id', $selectedDesaId);
        }
        if ($selectedTahun) {
            $statsQuery->where('tahun_anggaran', $selectedTahun);
        }
        if ($selectedSemester) {
            $statsQuery->where('periode_anggaran', $selectedSemester);
        }

        $stats = [
            'total_kegiatan' => $statsQuery->count(),
            'total_anggaran' => $statsQuery->sum('pagu_anggaran'),
            'rata_progres' => round($statsQuery->avg('progres_fisik') ?? 0, 2),
            'total_selesai' => (clone $statsQuery)->where('status', 'selesai')->count(),
        ];

        return view('transparansi.index', compact(
            'kecamatans', 'kegiatans', 'stats', 'search',
            'selectedKecId', 'selectedDesaId', 'selectedTahun', 'selectedSemester',
            'tahunList', 'desas'
        ));
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load(['desa.kecamatan', 'sumberDana', 'dokumens', 'progresUpdates']);
        return view('transparansi.show', compact('kegiatan'));
    }
}
