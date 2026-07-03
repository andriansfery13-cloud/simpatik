<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monev extends Model
{
    protected $fillable = [
        'desa_id', 'kegiatan_id', 'user_id', 'tanggal_monev',
        'aspek_perencanaan', 'aspek_keuangan', 'aspek_pelaksanaan',
        'aspek_fisik', 'aspek_pelaporan',
        'skor_perencanaan', 'skor_keuangan', 'skor_pelaksanaan',
        'skor_fisik', 'skor_pelaporan', 'skor_total',
        'ai_insight', 'ai_temuan'
    ];

    protected $casts = [
        'tanggal_monev' => 'date',
        'aspek_perencanaan' => 'array',
        'aspek_keuangan' => 'array',
        'aspek_pelaksanaan' => 'array',
        'aspek_fisik' => 'array',
        'aspek_pelaporan' => 'array',
        'skor_perencanaan' => 'decimal:2',
        'skor_keuangan' => 'decimal:2',
        'skor_pelaksanaan' => 'decimal:2',
        'skor_fisik' => 'decimal:2',
        'skor_pelaporan' => 'decimal:2',
        'skor_total' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getKategoriAttribute(): string
    {
        $skor = $this->skor_total;
        if ($skor >= 90) return 'Sangat Baik';
        if ($skor >= 75) return 'Baik';
        if ($skor >= 60) return 'Cukup';
        return 'Perlu Pembinaan';
    }

    public function getKategoriColorAttribute(): string
    {
        $kategori = $this->kategori;
        return match($kategori) {
            'Sangat Baik' => 'bg-green-100 text-green-800 border-green-200',
            'Baik' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Cukup' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'Perlu Pembinaan' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
