<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Resource $resource)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Resource $resource)
    {
        return $user->isAdmin() || ($user->isResourceManager() && $resource->manager_id === $user->id);
    }

    public function delete(User $user, Resource $resource)
    {
        return $user->isAdmin();
    }
}
