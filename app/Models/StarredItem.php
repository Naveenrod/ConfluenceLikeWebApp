<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StarredItem extends Model
{
    protected $fillable = [
        'user_id',
        'starrable_type',
        'starrable_id',
        'order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function starrable(): MorphTo
    {
        return $this->morphTo();
    }
}
