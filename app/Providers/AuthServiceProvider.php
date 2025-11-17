<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Page;
use App\Models\Space;
use App\Policies\CommentPolicy;
use App\Policies\PagePolicy;
use App\Policies\SpacePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Space::class => SpacePolicy::class,
        Page::class => PagePolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}

