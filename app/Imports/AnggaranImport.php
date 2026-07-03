<?php

namespace App\Imports;

use App\Models\Anggaran;
use App\Models\Desa;
use App\Models\SumberDana;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AnggaranImport implements ToModel, WithHeadingRow
{
    protected ?User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    public function model(array $row)
    {
        if (empty($row['tahun_anggaran']) || empty($row['pagu_rp'])) {
            return null;
        }

        // Resolve desa_id berdasarkan role user yang login
        $desaId = $this->resolveDesaId($row);

        // Resolve sumber dana
        $sumberDanaId = $row['id_sumber_dana'] ?? ($row['sumber_dana_id'] ?? null);
        if ($sumberDanaId && !is_numeric($sumberDanaId)) {
            $row['sumber_dana'] = $sumberDanaId;
            $sumberDanaId = null;
        }

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

    /**
     * Resolve desa_id berdasarkan role user:
     * - User Desa: paksa desa_id = desa user (abaikan data Excel)
     * - User Kecamatan: hanya boleh desa di kecamatan user
     * - User Kabupaten/Admin: bebas (sesuai data Excel)
     */
    private function resolveDesaId(array $row): int
    {
        // Jika user level desa → langsung kunci ke desa user
        if ($this->user && $this->user->isDesa()) {
            return $this->user->desa_id;
        }

        // Resolve desa_id dari data Excel
        $desaId = $row['id_desa'] ?? ($row['desa_id'] ?? null);
        if ($desaId && !is_numeric($desaId)) {
            $row['nama_desa'] = $desaId;
            $desaId = null;
        }

        if (!$desaId && !empty($row['nama_desa'])) {
            $query = Desa::where('nama', 'LIKE', '%' . trim($row['nama_desa']) . '%');

            // Jika user kecamatan, batasi pencarian desa hanya di kecamatan user
            if ($this->user && $this->user->isKecamatan()) {
                $query->where('kecamatan_id', $this->user->kecamatan_id);
            }

            $desa = $query->first();
            $desaId = $desa ? $desa->id : null;
        }

        // Jika user kecamatan, validasi bahwa desa_id memang ada di kecamatannya
        if ($this->user && $this->user->isKecamatan() && $desaId) {
            $valid = Desa::where('id', $desaId)
                ->where('kecamatan_id', $this->user->kecamatan_id)
                ->exists();
            if (!$valid) {
                $desaId = Desa::where('kecamatan_id', $this->user->kecamatan_id)->first()->id ?? null;
            }
        }

        // Fallback
        if (!$desaId) {
            if ($this->user && $this->user->isKecamatan()) {
                $desaId = Desa::where('kecamatan_id', $this->user->kecamatan_id)->first()->id ?? 1;
            } else {
                $desaId = Desa::first()->id ?? 1;
            }
        }

        return $desaId;
    }
}
