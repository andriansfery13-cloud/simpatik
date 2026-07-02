<?php

namespace App\Imports;

use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\SumberDana;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KegiatanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama_kegiatan']) || empty($row['nama_kegiatan'])) {
            return null;
        }

        // Resolve desa
        $desaId = $row['id_desa'] ?? null;
        if (!$desaId && !empty($row['nama_desa'])) {
            $desa = Desa::where('nama', 'LIKE', '%' . trim($row['nama_desa']) . '%')->first();
            $desaId = $desa ? $desa->id : null;
        }
        if (!$desaId) {
            $desaId = Desa::first()->id ?? 1;
        }

        // Resolve sumber dana
        $sumberDanaId = $row['id_sumber_dana'] ?? null;
        if (!$sumberDanaId && !empty($row['sumber_dana'])) {
            $sd = SumberDana::where('nama', 'LIKE', '%' . trim($row['sumber_dana']) . '%')->first();
            $sumberDanaId = $sd ? $sd->id : null;
        }
        if (!$sumberDanaId) {
            $sumberDanaId = SumberDana::first()->id ?? 1;
        }

        // Status mapping
        $statusMap = [
            'belum mulai' => 'belum_mulai',
            'berjalan' => 'berjalan',
            'selesai' => 'selesai',
            'terlambat' => 'terlambat',
        ];
        $status = 'belum_mulai';
        if (!empty($row['status'])) {
            $rawStatus = strtolower(trim($row['status']));
            $status = $statusMap[$rawStatus] ?? $rawStatus;
        }

        return new Kegiatan([
            'desa_id' => $desaId,
            'sumber_dana_id' => $sumberDanaId,
            'nama_kegiatan' => $row['nama_kegiatan'],
            'deskripsi' => $row['deskripsi'] ?? null,
            'lokasi' => $row['lokasi'] ?? null,
            'pagu_anggaran' => $row['pagu_anggaran'] ?? 0,
            'realisasi_anggaran' => $row['realisasi_anggaran'] ?? 0,
            'progres_fisik' => $row['progres_fisik'] ?? 0,
            'tanggal_mulai' => !empty($row['tanggal_mulai']) ? $row['tanggal_mulai'] : null,
            'tanggal_selesai' => !empty($row['tanggal_selesai']) ? $row['tanggal_selesai'] : null,
            'status' => $status,
            'pelaksana' => $row['pelaksana'] ?? null,
            'penanggung_jawab' => $row['penanggung_jawab'] ?? null,
            'tahun_anggaran' => $row['tahun_anggaran'] ?? now()->year,
            'periode_anggaran' => $row['periode_anggaran'] ?? null,
        ]);
    }
}
