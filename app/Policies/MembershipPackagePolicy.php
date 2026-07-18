<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MembershipPackage;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MembershipPackagePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MembershipPackage');
    }

    public function view(AuthUser $authUser, MembershipPackage $membershipPackage): bool
    {
        return $authUser->can('View:MembershipPackage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MembershipPackage');
    }

    public function update(AuthUser $authUser, MembershipPackage $membershipPackage): bool
    {
        return $authUser->can('Update:MembershipPackage');
    }

    public function delete(AuthUser $authUser, MembershipPackage $membershipPackage): bool
    {
        return $authUser->can('Delete:MembershipPackage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MembershipPackage');
    }

    public function restore(AuthUser $authUser, MembershipPackage $membershipPackage): bool
    {
        return $authUser->can('Restore:MembershipPackage');
    }

    public function forceDelete(AuthUser $authUser, MembershipPackage $membershipPackage): bool
    {
        return $authUser->can('ForceDelete:MembershipPackage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MembershipPackage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MembershipPackage');
    }

    public function replicate(AuthUser $authUser, MembershipPackage $membershipPackage): bool
    {
        return $authUser->can('Replicate:MembershipPackage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MembershipPackage');
    }
}
