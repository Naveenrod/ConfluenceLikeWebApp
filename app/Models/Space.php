<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'description',
        'owner_id',
        'icon',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(SpacePermission::class);
    }

    public function rootPages(): HasMany
    {
        return $this->hasMany(Page::class)->whereNull('parent_id');
    }
}

