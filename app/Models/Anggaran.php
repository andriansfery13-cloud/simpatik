<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggaran extends Model
{
    protected $fillable = [
        'desa_id', 'sumber_dana_id', 'tahun_anggaran',
        'pagu', 'realisasi', 'status_earmark', 'keterangan',
    ];

    protected $casts = [
        'pagu' => 'decimal:2',
        'realisasi' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function sumberDana(): BelongsTo
    {
        return $this->belongsTo(SumberDana::class);
    }

    public function getPersentaseRealisasiAttribute(): float
    {
        if ($this->pagu == 0) return 0;
        return round(($this->realisasi / $this->pagu) * 100, 2);
    }
}
