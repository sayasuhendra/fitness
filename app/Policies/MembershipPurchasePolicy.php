<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MembershipPurchase;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MembershipPurchasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MembershipPurchase');
    }

    public function view(AuthUser $authUser, MembershipPurchase $membershipPurchase): bool
    {
        return $authUser->can('View:MembershipPurchase');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MembershipPurchase');
    }

    public function update(AuthUser $authUser, MembershipPurchase $membershipPurchase): bool
    {
        return $authUser->can('Update:MembershipPurchase');
    }

    public function delete(AuthUser $authUser, MembershipPurchase $membershipPurchase): bool
    {
        return $authUser->can('Delete:MembershipPurchase');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MembershipPurchase');
    }

    public function restore(AuthUser $authUser, MembershipPurchase $membershipPurchase): bool
    {
        return $authUser->can('Restore:MembershipPurchase');
    }

    public function forceDelete(AuthUser $authUser, MembershipPurchase $membershipPurchase): bool
    {
        return $authUser->can('ForceDelete:MembershipPurchase');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MembershipPurchase');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MembershipPurchase');
    }

    public function replicate(AuthUser $authUser, MembershipPurchase $membershipPurchase): bool
    {
        return $authUser->can('Replicate:MembershipPurchase');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MembershipPurchase');
    }
}
