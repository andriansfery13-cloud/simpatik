<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\Kegiatan;
use App\Models\Anggaran;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Show the report generator form.
     */
    public function index()
    {
        $user = auth()->user();

        $tahunList = Kegiatan::select('tahun_anggaran')
            ->distinct()
            ->orderByDesc('tahun_anggaran')
            ->pluck('tahun_anggaran');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([now()->year]);
        }

        $kecamatans = collect();
        $desas = collect();
        $selectedKecamatanId = null;
        $selectedDesaId = null;
        $isKecamatanFixed = false;
        $isDesaFixed = false;

        if ($user->isDesa()) {
            $desa = Desa::with('kecamatan')->find($user->desa_id);
            if ($desa) {
                $kecamatans = collect([$desa->kecamatan]);
                $desas = collect([$desa]);
                $selectedKecamatanId = $desa->kecamatan_id;
                $selectedDesaId = $desa->id;
            }
            $isKecamatanFixed = true;
            $isDesaFixed = true;
        } elseif ($user->isKecamatan()) {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama')->get();
            $selectedKecamatanId = $user->kecamatan_id;
            $isKecamatanFixed = true;
        } else {
            $kecamatans = Kecamatan::orderBy('nama')->get();
        }

        return view('modules.laporan', compact(
            'kecamatans', 'tahunList', 'desas',
            'selectedKecamatanId', 'selectedDesaId',
            'isKecamatanFixed', 'isDesaFixed'
        ));
    }

    /**
     * Generate and preview the report.
     */
    public function generate(Request $request)
    {
        $user = auth()->user();

        // Enforce role-based limits before validation
        if ($user->isDesa()) {
            $request->merge([
                'kecamatan_id' => $user->desa->kecamatan_id ?? null,
                'desa_id' => $user->desa_id
            ]);
        } elseif ($user->isKecamatan()) {
            $request->merge([
                'kecamatan_id' => $user->kecamatan_id
            ]);
            if ($request->desa_id) {
                $desa = Desa::find($request->desa_id);
                if (!$desa || $desa->kecamatan_id !== $user->kecamatan_id) {
                    $request->merge(['desa_id' => null]);
                }
            }
        }

        $request->validate([
            'jenis_laporan' => 'required|in:rekapitulasi_anggaran,progres_fisik,evaluasi_kinerja,laporan_eksekutif',
            'tahun_anggaran' => 'required|integer',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        $jenis = $request->jenis_laporan;
        $tahun = $request->tahun_anggaran;
        $kecamatanId = $request->kecamatan_id;
        $desaId = $request->desa_id;
        $includeFoto = $request->boolean('include_foto');
        $includeKoordinat = $request->boolean('include_koordinat');

        // Build kegiatan query with filters
        $kegiatanQuery = Kegiatan::with(['desa.kecamatan', 'sumberDana'])
            ->where('tahun_anggaran', $tahun);

        if ($desaId) {
            $kegiatanQuery->where('desa_id', $desaId);
        } elseif ($kecamatanId) {
            $kegiatanQuery->whereHas('desa', fn($q) => $q->where('kecamatan_id', $kecamatanId));
        }

        $kegiatans = $kegiatanQuery->orderBy('nama_kegiatan')->get();

        // Determine wilayah name for header
        $wilayahLabel = 'Seluruh Kabupaten Bandung';
        $kecamatanNama = null;
        $desaNama = null;
        $kopSurat = null;

        if ($desaId) {
            $desa = Desa::with('kecamatan.kopSurat')->find($desaId);
            $wilayahLabel = 'Desa ' . $desa->nama . ', Kec. ' . $desa->kecamatan->nama;
            $kecamatanNama = $desa->kecamatan->nama;
            $desaNama = $desa->nama;
            $kopSurat = $desa->kecamatan->kopSurat ?? null;
        } elseif ($kecamatanId) {
            $kec = Kecamatan::with('kopSurat')->find($kecamatanId);
            $wilayahLabel = 'Kecamatan ' . $kec->nama;
            $kecamatanNama = $kec->nama;
            $kopSurat = $kec->kopSurat ?? null;
        } elseif ($user->isKecamatan()) {
            $kec = Kecamatan::with('kopSurat')->find($user->kecamatan_id);
            $kopSurat = $kec->kopSurat ?? null;
        }

        // Summary stats
        $summary = [
            'total_kegiatan' => $kegiatans->count(),
            'total_pagu' => $kegiatans->sum('pagu_anggaran'),
            'total_realisasi' => $kegiatans->sum('realisasi_anggaran'),
            'rata_progres_fisik' => round($kegiatans->avg('progres_fisik') ?? 0, 2),
            'kegiatan_selesai' => $kegiatans->where('status', 'selesai')->count(),
            'kegiatan_berjalan' => $kegiatans->where('status', 'berjalan')->count(),
            'kegiatan_terlambat' => $kegiatans->where('status', 'terlambat')->count(),
            'kegiatan_belum_mulai' => $kegiatans->where('status', 'belum_mulai')->count(),
            'persentase_keuangan' => 0,
        ];

        if ($summary['total_pagu'] > 0) {
            $summary['persentase_keuangan'] = round(($summary['total_realisasi'] / $summary['total_pagu']) * 100, 2);
        }

        // Per-kecamatan breakdown (for kabupaten-wide reports)
        $perKecamatan = collect();
        if (!$desaId) {
            $kecQuery = Kecamatan::orderBy('nama');
            if ($kecamatanId) {
                $kecQuery->where('id', $kecamatanId);
            }
            $allKecamatans = $kecQuery->get();

            foreach ($allKecamatans as $kec) {
                $kecKegiatans = $kegiatans->filter(function ($k) use ($kec) {
                    return $k->desa && $k->desa->kecamatan_id == $kec->id;
                });

                if ($kecKegiatans->isEmpty() && !$kecamatanId) continue;

                $pagu = $kecKegiatans->sum('pagu_anggaran');
                $realisasi = $kecKegiatans->sum('realisasi_anggaran');

                $perKecamatan->push([
                    'nama' => $kec->nama,
                    'camat' => $kec->camat,
                    'total_kegiatan' => $kecKegiatans->count(),
                    'total_pagu' => $pagu,
                    'total_realisasi' => $realisasi,
                    'persentase_keuangan' => $pagu > 0 ? round(($realisasi / $pagu) * 100, 2) : 0,
                    'rata_progres' => round($kecKegiatans->avg('progres_fisik') ?? 0, 2),
                    'selesai' => $kecKegiatans->where('status', 'selesai')->count(),
                    'berjalan' => $kecKegiatans->where('status', 'berjalan')->count(),
                    'terlambat' => $kecKegiatans->where('status', 'terlambat')->count(),
                    'belum_mulai' => $kecKegiatans->where('status', 'belum_mulai')->count(),
                ]);
            }
        }

        // Per-desa breakdown
        $perDesa = collect();
        $desaIds = $kegiatans->pluck('desa_id')->unique();
        $allDesas = Desa::with('kecamatan')->whereIn('id', $desaIds)->orderBy('nama')->get();

        foreach ($allDesas as $d) {
            $desaKegiatans = $kegiatans->where('desa_id', $d->id);
            $pagu = $desaKegiatans->sum('pagu_anggaran');
            $realisasi = $desaKegiatans->sum('realisasi_anggaran');

            $perDesa->push([
                'nama' => $d->nama,
                'kecamatan' => $d->kecamatan->nama ?? '-',
                'kepala_desa' => $d->kepala_desa,
                'total_kegiatan' => $desaKegiatans->count(),
                'total_pagu' => $pagu,
                'total_realisasi' => $realisasi,
                'persentase_keuangan' => $pagu > 0 ? round(($realisasi / $pagu) * 100, 2) : 0,
                'rata_progres' => round($desaKegiatans->avg('progres_fisik') ?? 0, 2),
                'selesai' => $desaKegiatans->where('status', 'selesai')->count(),
                'terlambat' => $desaKegiatans->where('status', 'terlambat')->count(),
            ]);
        }

        // Get jenis laporan label
        $jenisLabel = match($jenis) {
            'rekapitulasi_anggaran' => 'Laporan Rekapitulasi Realisasi Anggaran',
            'progres_fisik' => 'Laporan Progres Fisik Konstruksi',
            'evaluasi_kinerja' => 'Laporan Evaluasi & Kinerja (MONEV)',
            'laporan_eksekutif' => 'Laporan Eksekutif',
        };

        $data = compact(
            'jenis', 'jenisLabel', 'tahun', 'wilayahLabel',
            'kecamatanNama', 'desaNama',
            'kegiatans', 'summary', 'perKecamatan', 'perDesa',
            'includeFoto', 'includeKoordinat',
            'kecamatanId', 'desaId', 'kopSurat'
        );

        return view('laporan.preview', $data);
    }
}
