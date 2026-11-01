<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPraktikum extends Model
{
    protected $table = 'jadwal_praktikum';

    protected $fillable = ['mata_pelajaran', 'tanggal', 'jam', 'ruang', 'kelas', 'foto_path', 'dibuat_oleh'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
