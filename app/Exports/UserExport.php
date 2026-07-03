<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kecamatanId;

    public function __construct($kecamatanId = null)
    {
        $this->kecamatanId = $kecamatanId;
    }

    public function collection()
    {
        $query = User::with(['kecamatan', 'desa']);
        if ($this->kecamatanId) {
            $query->where('kecamatan_id', $this->kecamatanId);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID User',
            'Nama Lengkap',
            'Username',
            'Email',
            'Role',
            'ID Kecamatan',
            'Kecamatan',
            'ID Desa',
            'Desa',
            'Status Aktif',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->username,
            $user->email,
            $user->role,
            $user->kecamatan_id,
            $user->kecamatan->nama ?? '-',
            $user->desa_id,
            $user->desa->nama ?? '-',
            $user->is_active ? 'Aktif' : 'Non-Aktif',
        ];
    }
}
