<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanDokumen extends Model
{
    protected $table = 'kegiatan_dokumens';

    protected $fillable = [
        'kegiatan_id', 'user_id', 'tipe', 'file_path',
        'caption', 'latitude', 'longitude', 'taken_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'taken_at' => 'datetime',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTipeLabelAttribute(): string
    {
        return match($this->tipe) {
            'sebelum' => 'Sebelum',
            'proses' => 'Proses',
            'sesudah' => 'Sesudah',
            default => $this->tipe,
        };
    }
}
