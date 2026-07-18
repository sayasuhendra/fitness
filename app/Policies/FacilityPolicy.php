<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Facility;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FacilityPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Facility');
    }

    public function view(AuthUser $authUser, Facility $facility): bool
    {
        return $authUser->can('View:Facility');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Facility');
    }

    public function update(AuthUser $authUser, Facility $facility): bool
    {
        return $authUser->can('Update:Facility');
    }

    public function delete(AuthUser $authUser, Facility $facility): bool
    {
        return $authUser->can('Delete:Facility');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Facility');
    }

    public function restore(AuthUser $authUser, Facility $facility): bool
    {
        return $authUser->can('Restore:Facility');
    }

    public function forceDelete(AuthUser $authUser, Facility $facility): bool
    {
        return $authUser->can('ForceDelete:Facility');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Facility');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Facility');
    }

    public function replicate(AuthUser $authUser, Facility $facility): bool
    {
        return $authUser->can('Replicate:Facility');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Facility');
    }
}
