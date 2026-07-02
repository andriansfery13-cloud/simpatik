<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $fillable = [
        'kecamatan_id', 'kode', 'nama', 'kepala_desa', 'alamat',
        'telepon', 'latitude', 'longitude', 'jumlah_penduduk',
        'luas_wilayah', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_active' => 'boolean',
            'data_wilayah' => 'array',
            'data_aparatur' => 'array',
            'data_potensi' => 'array',
            'data_infrastruktur' => 'array',
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function anggarans(): HasMany
    {
        return $this->hasMany(Anggaran::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getTotalKegiatanAttribute(): int
    {
        return $this->kegiatans()->count();
    }

    public function getRataRataProgresAttribute(): float
    {
        return round($this->kegiatans()->avg('progres_fisik') ?? 0, 2);
    }

    public function getTotalPaguAttribute(): float
    {
        return $this->kegiatans()->sum('pagu_anggaran');
    }

    public function getTotalRealisasiAttribute(): float
    {
        return $this->kegiatans()->sum('realisasi_anggaran');
    }
}
