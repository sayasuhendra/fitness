<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Trainer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TrainerPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Trainer');
    }

    public function view(AuthUser $authUser, Trainer $trainer): bool
    {
        return $authUser->can('View:Trainer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Trainer');
    }

    public function update(AuthUser $authUser, Trainer $trainer): bool
    {
        return $authUser->can('Update:Trainer');
    }

    public function delete(AuthUser $authUser, Trainer $trainer): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function restore(AuthUser $authUser, Trainer $trainer): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function forceDelete(AuthUser $authUser, Trainer $trainer): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function replicate(AuthUser $authUser, Trainer $trainer): bool
    {
        return $authUser->can('Replicate:Trainer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Trainer');
    }
}
