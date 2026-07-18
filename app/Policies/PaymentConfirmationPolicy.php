<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PaymentConfirmation;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentConfirmationPolicy
{
    use ChecksAdminPermissions, HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'ViewAny', 'PaymentConfirmation');
    }

    public function view(User $user, PaymentConfirmation $paymentConfirmation): bool
    {
        return $this->allows($user, 'View', 'PaymentConfirmation');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'Create', 'PaymentConfirmation');
    }

    public function update(User $user, PaymentConfirmation $paymentConfirmation): bool
    {
        return $this->allows($user, 'Update', 'PaymentConfirmation');
    }

    public function delete(User $user, PaymentConfirmation $paymentConfirmation): bool
    {
        return $this->allows($user, 'Delete', 'PaymentConfirmation');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allows($user, 'DeleteAny', 'PaymentConfirmation');
    }
}
