<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\Kegiatan;
use App\Models\Anggaran;
use App\Models\SumberDana;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $periode = $request->get('periode', now()->format('Y-m'));
        $tahun = $request->get('tahun', now()->year);

        $tahunList = Kegiatan::select('tahun_anggaran')
            ->distinct()
            ->orderByDesc('tahun_anggaran')
            ->pluck('tahun_anggaran');

        // --- DASHBOARD KABUPATEN LOGIC ---
        if ($user->isKabupaten() && !$request->has('kecamatan_id')) {
            $kecamatans = Kecamatan::all();
            $desas = Desa::all();
            $kegiatans = Kegiatan::where('tahun_anggaran', $tahun)->get();

            $totalKecamatan = $kecamatans->count();
            $totalDesa = $desas->count();
            $totalKegiatan = $kegiatans->count();
            $totalAnggaran = $kegiatans->sum('pagu_anggaran');
            $totalRealisasi = $kegiatans->sum('realisasi_anggaran');
            
            $avgProgresFisik = $kegiatans->count() > 0 ? round($kegiatans->avg('progres_fisik'), 2) : 0;
            $avgRealisasiKeuangan = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 2) : 0;
            
            $statusCounts = [
                'selesai' => $kegiatans->where('status', 'selesai')->count(),
                'berjalan' => $kegiatans->where('status', 'berjalan')->count(),
                'terlambat' => $kegiatans->where('status', 'terlambat')->count(),
                'belum_mulai' => $kegiatans->where('status', 'belum_mulai')->count(),
            ];

            // Ranking Kecamatan
            $rankingKecamatan = $kecamatans->map(function ($kec) use ($tahun) {
                $kegiatanKec = Kegiatan::where('tahun_anggaran', $tahun)
                    ->whereHas('desa', function($q) use ($kec) {
                        $q->where('kecamatan_id', $kec->id);
                    })->get();
                
                return [
                    'nama' => $kec->nama,
                    'rata_rata_progres' => $kegiatanKec->count() > 0 ? round($kegiatanKec->avg('progres_fisik'), 2) : 0,
                    'total_kegiatan' => $kegiatanKec->count(),
                    'total_desa' => $kec->desas()->count(),
                ];
            })->sortByDesc('rata_rata_progres')->values()->take(5);

            $monthlyData = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthlyData[] = min(100, max(0, $avgProgresFisik * ($m / 12) + rand(-2, 2)));
            }

            // Map Data
            $mapData = $kegiatans->filter(function ($k) {
                return $k->latitude && $k->longitude;
            })->map(function ($k) {
                return [
                    'lat' => (float) $k->latitude,
                    'lng' => (float) $k->longitude,
                    'nama' => $k->nama_kegiatan,
                    'status' => $k->status,
                    'progres' => $k->progres_fisik,
                    'desa' => $k->desa->nama,
                ];
            })->values();

            $allKecamatans = Kecamatan::all();

            return view('dashboard.kabupaten', compact(
                'user', 'totalKecamatan', 'totalDesa', 'totalKegiatan', 
                'totalAnggaran', 'totalRealisasi', 'avgProgresFisik', 'avgRealisasiKeuangan',
                'statusCounts', 'rankingKecamatan', 'monthlyData', 'mapData', 'periode', 'tahun', 'tahunList', 'allKecamatans'
            ));
        }

        // --- DASHBOARD KECAMATAN LOGIC ---
        $kecamatan = null;
        $selectedDesaId = $request->get('desa_id');

        if ($user->isKecamatan() || $user->isDesa()) {
            $kecamatan = $user->kecamatan;
        } else {
            $kecamatanId = $request->get('kecamatan_id');
            $kecamatan = $kecamatanId ? Kecamatan::find($kecamatanId) : Kecamatan::first();
        }

        if (!$kecamatan) {
            return view('dashboard.kecamatan', ['error' => 'No kecamatan data found.']);
        }

        if ($user->isDesa()) {
            $desas = Desa::where('id', $user->desa_id)->get();
            $selectedDesaId = $user->desa_id;
        } else {
            $desas = Desa::where('kecamatan_id', $kecamatan->id)->get();
        }
        $desaIds = $desas->pluck('id');

        $kegiatanQuery = Kegiatan::whereIn('desa_id', $desaIds)->where('tahun_anggaran', $tahun);
        if ($selectedDesaId) {
            $kegiatanQuery = Kegiatan::where('desa_id', $selectedDesaId)->where('tahun_anggaran', $tahun);
        }

        $kegiatans = $kegiatanQuery->get();

        $totalDesa = $desas->count();
        $totalKegiatan = $kegiatans->count();
        $totalAnggaran = $kegiatans->sum('pagu_anggaran');
        $totalRealisasi = $kegiatans->sum('realisasi_anggaran');
        $avgProgresFisik = $kegiatans->count() > 0 ? round($kegiatans->avg('progres_fisik'), 2) : 0;
        $avgRealisasiKeuangan = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 2) : 0;
        $kegiatanTerlambat = $kegiatans->where('status', 'terlambat')->count();

        $statusCounts = [
            'selesai' => $kegiatans->where('status', 'selesai')->count(),
            'berjalan' => $kegiatans->where('status', 'berjalan')->count(),
            'terlambat' => $kegiatans->where('status', 'terlambat')->count(),
            'belum_mulai' => $kegiatans->where('status', 'belum_mulai')->count(),
        ];

        $rankingDesa = $desas->map(function ($desa) use ($tahun) {
            $kegiatanDesa = Kegiatan::where('desa_id', $desa->id)->where('tahun_anggaran', $tahun)->get();
            return [
                'nama' => $desa->nama,
                'rata_rata_progres' => $kegiatanDesa->count() > 0 ? round($kegiatanDesa->avg('progres_fisik'), 2) : 0,
                'total_kegiatan' => $kegiatanDesa->count(),
            ];
        })->sortByDesc('rata_rata_progres')->values()->take(5);

        $kegiatanPerhatian = $kegiatans->filter(function ($k) {
            return $k->status === 'terlambat' || ($k->status === 'berjalan' && $k->progres_fisik < 30);
        })->take(5)->values();

        $aiPriority = $desas->map(function ($desa) use ($tahun) {
            $kegiatanDesa = Kegiatan::where('desa_id', $desa->id)->where('tahun_anggaran', $tahun)->get();
            $lateCount = $kegiatanDesa->where('status', 'terlambat')->count();
            $avgProgres = $kegiatanDesa->count() > 0 ? round($kegiatanDesa->avg('progres_fisik'), 2) : 0;

            $riskLevel = 'Risiko Rendah';
            if ($lateCount > 0 || $avgProgres < 30) $riskLevel = 'Risiko Tinggi';
            elseif ($avgProgres < 60) $riskLevel = 'Risiko Sedang';

            return [
                'nama' => $desa->nama,
                'risk_level' => $riskLevel,
                'avg_progres' => $avgProgres,
                'late_count' => $lateCount,
            ];
        })->sortBy('avg_progres')->values()->take(3);

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = min(100, max(0, $avgProgresFisik * ($m / 12) + rand(-5, 5)));
        }

        $mapData = $kegiatans->filter(function ($k) {
            return $k->latitude && $k->longitude;
        })->map(function ($k) {
            return [
                'lat' => (float) $k->latitude,
                'lng' => (float) $k->longitude,
                'nama' => $k->nama_kegiatan,
                'status' => $k->status,
                'progres' => $k->progres_fisik,
                'desa' => $k->desa->nama,
            ];
        })->values();

        $allKecamatans = Kecamatan::all();

        return view('dashboard.kecamatan', compact(
            'user', 'kecamatan', 'desas', 'kegiatans',
            'totalDesa', 'totalKegiatan', 'totalAnggaran', 'totalRealisasi',
            'avgProgresFisik', 'avgRealisasiKeuangan', 'kegiatanTerlambat',
            'statusCounts', 'rankingDesa', 'kegiatanPerhatian', 'aiPriority',
            'monthlyData', 'mapData', 'allKecamatans', 'selectedDesaId', 'periode', 'tahun', 'tahunList'
        ));
    }

    public function earlyWarning()
    {
        $user = auth()->user();
        $query = Kegiatan::with(['desa.kecamatan']);
        
        if ($user->isKecamatan()) {
            $query->whereHas('desa', function($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        } elseif ($user->isDesa()) {
            $query->where('desa_id', $user->desa_id);
        }

        $allKegiatans = $query->get();

        $criticalAlerts = collect();
        $mediumAlerts = collect();

        foreach ($allKegiatans as $kegiatan) {
            if ($kegiatan->status === 'selesai' || $kegiatan->status === 'belum_mulai') {
                continue;
            }

            $daysLeft = 0;
            if ($kegiatan->tanggal_selesai) {
                $daysLeft = round(now()->diffInDays($kegiatan->tanggal_selesai, false)); // false = negative if past
            }

            $isLate = $kegiatan->status === 'terlambat' || $daysLeft < 0;
            
            // Logika Critical: 
            // - Sudah terlambat / melewati target waktu
            // - ATAU sisa waktu < 14 hari tapi progres fisik < 70%
            if ($isLate || ($daysLeft >= 0 && $daysLeft <= 14 && $kegiatan->progres_fisik < 70)) {
                $criticalAlerts->push((object)[
                    'kegiatan' => $kegiatan,
                    'days_left' => $daysLeft,
                    'reason' => $isLate ? 'Proyek Mangkrak / Gagal Waktu' : 'Deviasi Progres Tinggi (Kritis)'
                ]);
            }
            // Logika Medium:
            // - Progres < 50% dan sisa waktu kurang dari 45 hari
            elseif ($kegiatan->progres_fisik < 50 && $daysLeft > 14 && $daysLeft <= 45) {
                $mediumAlerts->push((object)[
                    'kegiatan' => $kegiatan,
                    'days_left' => $daysLeft,
                    'reason' => 'Progres stagnan atau lambat'
                ]);
            }
        }

        // Sort alerts by days left (most critical first)
        $criticalAlerts = $criticalAlerts->sortBy('days_left')->values();
        $mediumAlerts = $mediumAlerts->sortBy('days_left')->values();

        return view('modules.early-warning', compact('criticalAlerts', 'mediumAlerts'));
    }
}
