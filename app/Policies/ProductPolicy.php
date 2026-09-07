<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Product');
    }

    public function view(AuthUser $authUser, Product $product): bool
    {
        return $authUser->can('View:Product');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Product');
    }

    public function update(AuthUser $authUser, Product $product): bool
    {
        return $authUser->can('Update:Product');
    }

    public function delete(AuthUser $authUser, Product $product): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function restore(AuthUser $authUser, Product $product): bool
    {
        return method_exists($authUser, 'hasAnyRole') && $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function forceDelete(AuthUser $authUser, Product $product): bool
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

    public function replicate(AuthUser $authUser, Product $product): bool
    {
        return $authUser->can('Replicate:Product');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Product');
    }
}
