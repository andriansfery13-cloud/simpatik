<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'kecamatan_id', 'desa_id', 'nip',
        'jabatan', 'telepon', 'foto', 'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    // Role check helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKabupaten(): bool
    {
        return in_array($this->role, ['admin', 'bupati', 'sekda', 'dpmd', 'bappeda', 'inspektorat']);
    }

    public function isKecamatan(): bool
    {
        return in_array($this->role, ['camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan']);
    }

    public function isDesa(): bool
    {
        return in_array($this->role, ['kepala_desa', 'sekretaris_desa', 'operator_desa']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'bupati' => 'Bupati',
            'sekda' => 'Sekretaris Daerah',
            'dpmd' => 'DPMD',
            'bappeda' => 'Bappeda',
            'inspektorat' => 'Inspektorat',
            'camat' => 'Camat',
            'sekcam' => 'Sekretaris Camat',
            'kasi_pmd' => 'Kasi PMD',
            'operator_kecamatan' => 'Operator Kecamatan',
            'kepala_desa' => 'Kepala Desa',
            'sekretaris_desa' => 'Sekretaris Desa',
            'operator_desa' => 'Operator Desa',
            default => $this->role,
        };
    }

    public function getLevelAttribute(): string
    {
        if ($this->isKabupaten()) return 'kabupaten';
        if ($this->isKecamatan()) return 'kecamatan';
        return 'desa';
    }
}
