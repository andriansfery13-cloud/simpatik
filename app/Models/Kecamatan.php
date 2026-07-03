<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $fillable = [
        'kode', 'nama', 'camat', 'alamat', 'telepon',
        'latitude', 'longitude', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class);
    }

    public function kopSurat()
    {
        return $this->morphOne(KopSurat::class, 'koppable');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getTotalDesaAttribute(): int
    {
        return $this->desas()->count();
    }

    public function getTotalKegiatanAttribute(): int
    {
        return Kegiatan::whereHas('desa', fn($q) => $q->where('kecamatan_id', $this->id))->count();
    }

    public function getTotalAnggaranAttribute(): float
    {
        return Kegiatan::whereHas('desa', fn($q) => $q->where('kecamatan_id', $this->id))->sum('pagu_anggaran');
    }

    public function getRealisasiAnggaranAttribute(): float
    {
        return Kegiatan::whereHas('desa', fn($q) => $q->where('kecamatan_id', $this->id))->sum('realisasi_anggaran');
    }

    public function getRataRataProgresFisikAttribute(): float
    {
        $avg = Kegiatan::whereHas('desa', fn($q) => $q->where('kecamatan_id', $this->id))->avg('progres_fisik');
        return round($avg ?? 0, 2);
    }
}
