<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Anggaran;

class Kegiatan extends Model
{
    protected $fillable = [
        'desa_id', 'sumber_dana_id', 'nama_kegiatan', 'deskripsi',
        'lokasi', 'latitude', 'longitude', 'pagu_anggaran',
        'realisasi_anggaran', 'progres_fisik', 'tanggal_mulai',
        'tanggal_selesai', 'tanggal_realisasi_selesai', 'status',
        'pelaksana', 'penanggung_jawab', 'tahun_anggaran', 'periode_anggaran', 'catatan',
    ];

    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
        'realisasi_anggaran' => 'decimal:2',
        'progres_fisik' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_realisasi_selesai' => 'date',
    ];

    protected static function booted()
    {
        $updateAnggaran = function ($desa_id, $sumber_dana_id, $tahun) {
            if (!$desa_id || !$sumber_dana_id || !$tahun) return;
            
            $anggaran = Anggaran::where('desa_id', $desa_id)
                ->where('sumber_dana_id', $sumber_dana_id)
                ->where('tahun_anggaran', $tahun)
                ->first();

            if ($anggaran) {
                // Hanya hitung realisasi dari kegiatan yang berjalan atau selesai
                $totalDipakai = static::where('desa_id', $desa_id)
                    ->where('sumber_dana_id', $sumber_dana_id)
                    ->where('tahun_anggaran', $tahun)
                    ->whereIn('status', ['berjalan', 'selesai'])
                    ->sum('pagu_anggaran');
                
                $anggaran->update(['realisasi' => $totalDipakai]);
            }
        };

        static::saved(function ($kegiatan) use ($updateAnggaran) {
            $updateAnggaran($kegiatan->desa_id, $kegiatan->sumber_dana_id, $kegiatan->tahun_anggaran);
            
            // Jika ada perubahan pada foreign key, update juga anggaran yang lama
            if ($kegiatan->wasChanged(['desa_id', 'sumber_dana_id', 'tahun_anggaran'])) {
                $updateAnggaran(
                    $kegiatan->getOriginal('desa_id'),
                    $kegiatan->getOriginal('sumber_dana_id'),
                    $kegiatan->getOriginal('tahun_anggaran')
                );
            }
        });

        static::deleted(function ($kegiatan) use ($updateAnggaran) {
            $updateAnggaran($kegiatan->desa_id, $kegiatan->sumber_dana_id, $kegiatan->tahun_anggaran);
        });
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function sumberDana(): BelongsTo
    {
        return $this->belongsTo(SumberDana::class);
    }

    public function progresUpdates(): HasMany
    {
        return $this->hasMany(KegiatanProgres::class);
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(KegiatanDokumen::class);
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }

    public function getPersentaseKeuanganAttribute(): float
    {
        if ($this->pagu_anggaran == 0) return 0;
        return round(($this->realisasi_anggaran / $this->pagu_anggaran) * 100, 2);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'belum_mulai' => 'Belum Mulai',
            'berjalan' => 'Berjalan',
            'selesai' => 'Selesai',
            'terlambat' => 'Terlambat',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'belum_mulai' => 'secondary',
            'berjalan' => 'info',
            'selesai' => 'success',
            'terlambat' => 'danger',
            default => 'secondary',
        };
    }

    public function getIsLateAttribute(): bool
    {
        if ($this->status === 'selesai') return false;
        if (!$this->tanggal_selesai) return false;
        return now()->greaterThan($this->tanggal_selesai);
    }
}
