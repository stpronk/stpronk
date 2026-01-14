<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Stpronk\UrlDissector\Models\Host;

class HostPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Host');
    }

    public function view(AuthUser $authUser, Host $host): bool
    {
        return $authUser->can('View:Host');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Host');
    }

    public function update(AuthUser $authUser, Host $host): bool
    {
        return $authUser->can('Update:Host');
    }

    public function delete(AuthUser $authUser, Host $host): bool
    {
        return $authUser->can('Delete:Host');
    }

    public function restore(AuthUser $authUser, Host $host): bool
    {
        return $authUser->can('Restore:Host');
    }

    public function forceDelete(AuthUser $authUser, Host $host): bool
    {
        return $authUser->can('ForceDelete:Host');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Host');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Host');
    }

    public function replicate(AuthUser $authUser, Host $host): bool
    {
        return $authUser->can('Replicate:Host');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Host');
    }

}
