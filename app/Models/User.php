<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'username', 'nama_lengkap', 'kelas', 'nis', 'jurusan',
    'tahun_masuk', 'password', 'role', 'membership_status', 'approved_at', 'approved_by', 'activated_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'activated_at' => 'datetime',
            'tahun_masuk' => 'integer',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    public function isApproved(): bool
    {
        return $this->membership_status === 'approved';
    }

    public function isActivated(): bool
    {
        return $this->isAdmin() || $this->isGuru() || $this->activated_at !== null;
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    public function nilaiPraktikum(): HasMany
    {
        return $this->hasMany(NilaiPraktikum::class);
    }

    public function jadwalPraktikumDibuat(): HasMany
    {
        return $this->hasMany(JadwalPraktikum::class, 'dibuat_oleh');
    }

    public function pengumumanDibuat(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'dibuat_oleh');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}
