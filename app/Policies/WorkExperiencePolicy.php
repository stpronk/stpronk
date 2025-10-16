<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WorkExperience;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkExperiencePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WorkExperience');
    }

    public function view(AuthUser $authUser, WorkExperience $workExperience): bool
    {
        return $authUser->can('View:WorkExperience');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WorkExperience');
    }

    public function update(AuthUser $authUser, WorkExperience $workExperience): bool
    {
        return $authUser->can('Update:WorkExperience');
    }

    public function delete(AuthUser $authUser, WorkExperience $workExperience): bool
    {
        return $authUser->can('Delete:WorkExperience');
    }

    public function restore(AuthUser $authUser, WorkExperience $workExperience): bool
    {
        return $authUser->can('Restore:WorkExperience');
    }

    public function forceDelete(AuthUser $authUser, WorkExperience $workExperience): bool
    {
        return $authUser->can('ForceDelete:WorkExperience');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WorkExperience');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WorkExperience');
    }

    public function replicate(AuthUser $authUser, WorkExperience $workExperience): bool
    {
        return $authUser->can('Replicate:WorkExperience');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WorkExperience');
    }

}