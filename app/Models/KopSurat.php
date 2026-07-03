<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class KopSurat extends Model
{
    protected $fillable = [
        'koppable_id',
        'koppable_type',
        'pemerintah',
        'instansi',
        'alamat',
        'kontak',
        'logo_path',
    ];

    public function koppable(): MorphTo
    {
        return $this->morphTo();
    }
}
