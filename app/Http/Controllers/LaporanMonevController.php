<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Anggaran;
use App\Models\Monev;
use Illuminate\Http\Request;

class LaporanMonevController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->isDesa()) {
            abort(403, 'Hanya Kecamatan/Kabupaten yang dapat mencetak Laporan.');
        }

        $kecamatans = collect();
        $selectedKecamatanId = $request->kecamatan_id;
        $isKecamatanFixed = false;

        if ($user->isKecamatan()) {
            $kecamatans = \App\Models\Kecamatan::where('id', $user->kecamatan_id)->get();
            $selectedKecamatanId = $user->kecamatan_id;
            $isKecamatanFixed = true;
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama')->get();
        } else {
            $kecamatans = \App\Models\Kecamatan::orderBy('nama')->get();
            if ($selectedKecamatanId) {
                $desas = Desa::where('kecamatan_id', $selectedKecamatanId)->orderBy('nama')->get();
            } else {
                $desas = Desa::orderBy('nama')->get();
            }
        }

        $selectedDesa = $request->desa_id ? Desa::find($request->desa_id) : null;
        $anggarans = collect();

        if ($selectedDesa) {
            $anggarans = Anggaran::with('sumberDana')->where('desa_id', $selectedDesa->id)->get();
        }

        return view('laporan.monev_index', compact('kecamatans', 'selectedKecamatanId', 'isKecamatanFixed', 'desas', 'selectedDesa', 'anggarans'));
    }

    public function cetak(Request $request)
    {
        $user = auth()->user();
        if ($user->isDesa()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'anggaran_id' => 'required|exists:anggarans,id',
            'tim_pembina' => 'required|array',
            'tim_pembina.*.nama' => 'required|string',
            'tim_pembina.*.jabatan' => 'required|string',
            'ketua_bpd' => 'required|string',
            'ketua_lpmd' => 'required|string',
            'kepala_desa' => 'required|string',
            'camat' => 'nullable|string',
            'tahap' => 'nullable|string',
        ]);

        $anggaran = Anggaran::with(['desa.kecamatan', 'sumberDana'])->findOrFail($request->anggaran_id);
        
        $kegiatans = \App\Models\Kegiatan::with('monev')
            ->where('desa_id', $anggaran->desa_id)
            ->where('sumber_dana_id', $anggaran->sumber_dana_id)
            ->where('tahun_anggaran', $anggaran->tahun_anggaran)
            ->get();

        // Get Kop Surat of the Kecamatan
        $kopSurat = $anggaran->desa->kecamatan->kopSurat ?? null;

        $timPembina = $request->tim_pembina;
        $ketuaBpd = $request->ketua_bpd;
        $ketuaLpmd = $request->ketua_lpmd;
        $kepalaDesa = $request->kepala_desa;
        $camat = $request->camat;
        $tahap = $request->tahap;

        return view('laporan.monev_cetak', compact(
            'anggaran', 'kegiatans', 'kopSurat', 'timPembina', 'ketuaBpd', 'ketuaLpmd', 'kepalaDesa', 'camat', 'tahap'
        ));
    }
}
