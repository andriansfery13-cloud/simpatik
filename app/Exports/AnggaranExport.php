<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnggaranExport implements FromCollection, WithHeadings, WithMapping
{
    protected $anggarans;

    public function __construct($anggarans)
    {
        $this->anggarans = $anggarans;
    }

    public function collection()
    {
        return $this->anggarans;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Desa',
            'Nama Kecamatan',
            'Sumber Dana',
            'Tahun Anggaran',
            'Pagu (Rp)',
            'Realisasi (Rp)',
            'Persentase Realisasi (%)',
            'Status Earmark',
            'Keterangan',
        ];
    }

    public function map($anggaran): array
    {
        return [
            $anggaran->id,
            $anggaran->desa->nama ?? '',
            $anggaran->desa->kecamatan->nama ?? '',
            $anggaran->sumberDana->nama ?? '',
            $anggaran->tahun_anggaran,
            $anggaran->pagu,
            $anggaran->realisasi,
            $anggaran->persentase_realisasi,
            $anggaran->status_earmark,
            $anggaran->keterangan,
        ];
    }
}
