<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KegiatanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kegiatans;

    public function __construct($kegiatans)
    {
        $this->kegiatans = $kegiatans;
    }

    public function collection()
    {
        return $this->kegiatans;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Desa',
            'Nama Kecamatan',
            'Sumber Dana',
            'Nama Kegiatan',
            'Deskripsi',
            'Lokasi',
            'Pagu Anggaran',
            'Realisasi Anggaran',
            'Progres Fisik (%)',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Pelaksana',
            'Penanggung Jawab',
            'Tahun Anggaran',
            'Periode Anggaran',
        ];
    }

    public function map($kegiatan): array
    {
        return [
            $kegiatan->id,
            $kegiatan->desa->nama ?? '',
            $kegiatan->desa->kecamatan->nama ?? '',
            $kegiatan->sumberDana->nama ?? '',
            $kegiatan->nama_kegiatan,
            $kegiatan->deskripsi,
            $kegiatan->lokasi,
            $kegiatan->pagu_anggaran,
            $kegiatan->realisasi_anggaran,
            $kegiatan->progres_fisik,
            $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->format('Y-m-d') : '',
            $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->format('Y-m-d') : '',
            $kegiatan->status_label,
            $kegiatan->pelaksana,
            $kegiatan->penanggung_jawab,
            $kegiatan->tahun_anggaran,
            $kegiatan->periode_anggaran,
        ];
    }
}
