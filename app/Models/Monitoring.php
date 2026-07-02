<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitoring extends Model
{
    protected $fillable = [
        'kegiatan_id', 'user_id', 'tanggal_monitoring',
        'kondisi_lapangan', 'catatan', 'progres_saat_monitoring',
        'latitude', 'longitude',
    ];

    protected $casts = [
        'tanggal_monitoring' => 'date',
        'progres_saat_monitoring' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function temuans(): HasMany
    {
        return $this->hasMany(Temuan::class);
    }
}
