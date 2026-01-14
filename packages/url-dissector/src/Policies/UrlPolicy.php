<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Stpronk\UrlDissector\Models\Url;

class UrlPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Url');
    }

    public function view(AuthUser $authUser, Url $url): bool
    {
        return $authUser->can('View:Url');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Url');
    }

    public function update(AuthUser $authUser, Url $url): bool
    {
        return $authUser->can('Update:Url');
    }

    public function delete(AuthUser $authUser, Url $url): bool
    {
        return $authUser->can('Delete:Url');
    }

    public function restore(AuthUser $authUser, Url $url): bool
    {
        return $authUser->can('Restore:Url');
    }

    public function forceDelete(AuthUser $authUser, Url $url): bool
    {
        return $authUser->can('ForceDelete:Url');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Url');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Url');
    }

    public function replicate(AuthUser $authUser, Url $url): bool
    {
        return $authUser->can('Replicate:Url');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Url');
    }

}
