<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiPraktikum extends Model
{
    protected $table = 'nilai_praktikum';

    protected $fillable = ['user_id', 'mata_pelajaran', 'nilai', 'semester'];

    protected function casts(): array
    {
        return ['nilai' => 'decimal:2'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
