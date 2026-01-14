<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Stpronk\Todos\Models\TodoCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TodoCategory');
    }

    public function view(AuthUser $authUser, TodoCategory $todoCategory): bool
    {
        return $authUser->can('View:TodoCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TodoCategory');
    }

    public function update(AuthUser $authUser, TodoCategory $todoCategory): bool
    {
        return $authUser->can('Update:TodoCategory');
    }

    public function delete(AuthUser $authUser, TodoCategory $todoCategory): bool
    {
        return $authUser->can('Delete:TodoCategory');
    }

    public function restore(AuthUser $authUser, TodoCategory $todoCategory): bool
    {
        return $authUser->can('Restore:TodoCategory');
    }

    public function forceDelete(AuthUser $authUser, TodoCategory $todoCategory): bool
    {
        return $authUser->can('ForceDelete:TodoCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TodoCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TodoCategory');
    }

    public function replicate(AuthUser $authUser, TodoCategory $todoCategory): bool
    {
        return $authUser->can('Replicate:TodoCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TodoCategory');
    }

}