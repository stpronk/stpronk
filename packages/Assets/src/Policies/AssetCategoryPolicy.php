<?php

declare(strict_types=1);

namespace Stpronk\Assets\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Stpronk\Assets\Models\AssetCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AssetCategory');
    }

    public function view(AuthUser $authUser, AssetCategory $category): bool
    {
        return $authUser->can('View:AssetCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AssetCategory');
    }

    public function update(AuthUser $authUser, AssetCategory $category): bool
    {
        return $authUser->can('Update:AssetCategory');
    }

    public function delete(AuthUser $authUser, AssetCategory $category): bool
    {
        return $authUser->can('Delete:AssetCategory');
    }

    public function restore(AuthUser $authUser, AssetCategory $category): bool
    {
        return $authUser->can('Restore:AssetCategory');
    }

    public function forceDelete(AuthUser $authUser, AssetCategory $category): bool
    {
        return $authUser->can('ForceDelete:AssetCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AssetCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AssetCategory');
    }

    public function replicate(AuthUser $authUser, AssetCategory $category): bool
    {
        return $authUser->can('Replicate:AssetCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AssetCategory');
    }

}
