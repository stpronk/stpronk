<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Stpronk\UrlDissector\Models\Path;

class PathPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Path');
    }

    public function view(AuthUser $authUser, Path $path): bool
    {
        return $authUser->can('View:Path');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Path');
    }

    public function update(AuthUser $authUser, Path $path): bool
    {
        return $authUser->can('Update:Path');
    }

    public function delete(AuthUser $authUser, Path $path): bool
    {
        return $authUser->can('Delete:Path');
    }

    public function restore(AuthUser $authUser, Path $path): bool
    {
        return $authUser->can('Restore:Path');
    }

    public function forceDelete(AuthUser $authUser, Path $path): bool
    {
        return $authUser->can('ForceDelete:Path');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Path');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Path');
    }

    public function replicate(AuthUser $authUser, Path $path): bool
    {
        return $authUser->can('Replicate:Path');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Path');
    }

}
