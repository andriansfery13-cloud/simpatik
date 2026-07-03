<?php

namespace App\Imports;

use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\SumberDana;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KegiatanImport implements ToModel, WithHeadingRow
{
    protected ?User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    public function model(array $row)
    {
        if (!isset($row['nama_kegiatan']) || empty($row['nama_kegiatan'])) {
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
                // Desa tidak berada di kecamatan user, ambil desa pertama di kecamatan user
                $desaId = Desa::where('kecamatan_id', $this->user->kecamatan_id)->first()->id ?? null;
            }
        }

        // Fallback: ambil desa pertama yang sesuai akses user
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
