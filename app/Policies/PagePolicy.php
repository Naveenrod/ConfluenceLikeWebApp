<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\Space;
use App\Models\User;

class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Page $page): bool
    {
        $space = $page->space;
        return $space->owner_id === $user->id
            || $space->permissions()->where('user_id', $user->id)->exists()
            || $page->permissions()->where('user_id', $user->id)->where('permission_type', 'view')->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Page $page): bool
    {
        $space = $page->space;
        return $page->author_id === $user->id
            || $space->owner_id === $user->id
            || $space->permissions()->where('user_id', $user->id)->whereIn('role', ['editor', 'admin'])->exists()
            || $page->permissions()->where('user_id', $user->id)->whereIn('permission_type', ['edit', 'delete'])->exists();
    }

    public function delete(User $user, Page $page): bool
    {
        $space = $page->space;
        return $page->author_id === $user->id
            || $space->owner_id === $user->id
            || $space->permissions()->where('user_id', $user->id)->where('role', 'admin')->exists()
            || $page->permissions()->where('user_id', $user->id)->where('permission_type', 'delete')->exists();
    }
}

