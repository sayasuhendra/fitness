<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FitnessClass;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FitnessClassPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FitnessClass');
    }

    public function view(AuthUser $authUser, FitnessClass $fitnessClass): bool
    {
        return $authUser->can('View:FitnessClass');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FitnessClass');
    }

    public function update(AuthUser $authUser, FitnessClass $fitnessClass): bool
    {
        return $authUser->can('Update:FitnessClass');
    }

    public function delete(AuthUser $authUser, FitnessClass $fitnessClass): bool
    {
        return $authUser->can('Delete:FitnessClass');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FitnessClass');
    }

    public function restore(AuthUser $authUser, FitnessClass $fitnessClass): bool
    {
        return $authUser->can('Restore:FitnessClass');
    }

    public function forceDelete(AuthUser $authUser, FitnessClass $fitnessClass): bool
    {
        return $authUser->can('ForceDelete:FitnessClass');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FitnessClass');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FitnessClass');
    }

    public function replicate(AuthUser $authUser, FitnessClass $fitnessClass): bool
    {
        return $authUser->can('Replicate:FitnessClass');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FitnessClass');
    }
}
