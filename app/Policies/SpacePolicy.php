<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;

class SpacePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Space $space): bool
    {
        return $space->owner_id === $user->id 
            || $space->permissions()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Space $space): bool
    {
        return $space->owner_id === $user->id
            || $space->permissions()->where('user_id', $user->id)->where('role', 'admin')->exists();
    }

    public function delete(User $user, Space $space): bool
    {
        return $space->owner_id === $user->id;
    }
}

