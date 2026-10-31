<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    protected $table = 'chat';

    protected $fillable = ['user_id', 'pesan', 'media_path', 'media_type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
