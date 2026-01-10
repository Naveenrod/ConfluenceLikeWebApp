<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function spaces()
    {
        return $this->hasMany(Space::class, 'owner_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function starredItems()
    {
        return $this->hasMany(StarredItem::class);
    }

    public function starredSpaces()
    {
        return $this->morphedByMany(Space::class, 'starrable', 'starred_items')
            ->withTimestamps()
            ->orderBy('starred_items.order');
    }

    public function starredPages()
    {
        return $this->morphedByMany(Page::class, 'starrable', 'starred_items')
            ->withTimestamps()
            ->orderBy('starred_items.order');
    }

    public function recentViews()
    {
        return $this->hasMany(RecentView::class)->orderByDesc('viewed_at');
    }

    public function pageLikes()
    {
        return $this->hasMany(PageLike::class);
    }

    public function likedPages()
    {
        return $this->belongsToMany(Page::class, 'page_likes')->withTimestamps();
    }

    public function hasStarred($model): bool
    {
        return $this->starredItems()
            ->where('starrable_type', get_class($model))
            ->where('starrable_id', $model->id)
            ->exists();
    }

    public function hasLiked(Page $page): bool
    {
        return $this->pageLikes()->where('page_id', $page->id)->exists();
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }
}

