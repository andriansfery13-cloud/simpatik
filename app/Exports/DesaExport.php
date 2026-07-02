<?php

namespace App\Exports;

use App\Models\Desa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DesaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $desas;

    public function __construct($desas)
    {
        $this->desas = $desas;
    }

    public function collection()
    {
        return $this->desas;
    }

    public function headings(): array
    {
        return [
            'ID',
            'ID Kecamatan',
            'Nama Kecamatan',
            'Kode Desa',
            'Nama Desa',
            'Kepala Desa',
            'Alamat',
            'Telepon',
            'Latitude',
            'Longitude',
            'Jumlah Penduduk',
            'Luas Wilayah (Ha)',
            'Status Aktif'
        ];
    }

    public function map($desa): array
    {
        return [
            $desa->id,
            $desa->kecamatan_id,
            $desa->kecamatan->nama ?? '',
            $desa->kode,
            $desa->nama,
            $desa->kepala_desa,
            $desa->alamat,
            $desa->telepon,
            $desa->latitude,
            $desa->longitude,
            $desa->jumlah_penduduk,
            $desa->luas_wilayah,
            $desa->is_active ? 'Aktif' : 'Tidak Aktif'
        ];
    }
}
