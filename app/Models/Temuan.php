<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Temuan extends Model
{
    protected $fillable = [
        'monitoring_id', 'deskripsi_temuan', 'tingkat',
        'rekomendasi', 'tindak_lanjut', 'batas_waktu_tindak_lanjut',
        'status', 'tanggal_selesai',
    ];

    protected $casts = [
        'batas_waktu_tindak_lanjut' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class);
    }

    public function getTingkatLabelAttribute(): string
    {
        return match($this->tingkat) {
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
            'kritis' => 'Kritis',
            default => $this->tingkat,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => $this->status,
        };
    }
}
