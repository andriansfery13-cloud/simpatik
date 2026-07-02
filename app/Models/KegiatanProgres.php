<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanProgres extends Model
{
    protected $table = 'kegiatan_progres';

    protected $fillable = [
        'kegiatan_id', 'user_id', 'progres_fisik',
        'progres_keuangan', 'keterangan', 'tanggal_update',
    ];

    protected $casts = [
        'progres_fisik' => 'decimal:2',
        'progres_keuangan' => 'decimal:2',
        'tanggal_update' => 'date',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
