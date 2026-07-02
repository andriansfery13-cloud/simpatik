<?php

namespace App\Imports;

use App\Models\Anggaran;
use App\Models\Desa;
use App\Models\SumberDana;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AnggaranImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['tahun_anggaran']) || empty($row['pagu_rp'])) {
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

        $statusEarmark = 'non-earmarked';
        if (!empty($row['status_earmark'])) {
            $val = strtolower(trim($row['status_earmark']));
            if (in_array($val, ['earmarked', 'non-earmarked'])) {
                $statusEarmark = $val;
            }
        }

        return new Anggaran([
            'desa_id' => $desaId,
            'sumber_dana_id' => $sumberDanaId,
            'tahun_anggaran' => $row['tahun_anggaran'],
            'pagu' => $row['pagu_rp'] ?? 0,
            'realisasi' => $row['realisasi_rp'] ?? 0,
            'status_earmark' => $statusEarmark,
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }
}
