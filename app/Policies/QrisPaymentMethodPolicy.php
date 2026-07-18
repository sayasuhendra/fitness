<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QrisPaymentMethod;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class QrisPaymentMethodPolicy
{
    use ChecksAdminPermissions, HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'ViewAny', 'QrisPaymentMethod');
    }

    public function view(User $user, QrisPaymentMethod $qrisPaymentMethod): bool
    {
        return $this->allows($user, 'View', 'QrisPaymentMethod');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'Create', 'QrisPaymentMethod');
    }

    public function update(User $user, QrisPaymentMethod $qrisPaymentMethod): bool
    {
        return $this->allows($user, 'Update', 'QrisPaymentMethod');
    }

    public function delete(User $user, QrisPaymentMethod $qrisPaymentMethod): bool
    {
        return $this->allows($user, 'Delete', 'QrisPaymentMethod');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allows($user, 'DeleteAny', 'QrisPaymentMethod');
    }
}
