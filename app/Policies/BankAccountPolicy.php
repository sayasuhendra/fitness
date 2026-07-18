<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BankAccount;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankAccountPolicy
{
    use ChecksAdminPermissions, HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'ViewAny', 'BankAccount');
    }

    public function view(User $user, BankAccount $bankAccount): bool
    {
        return $this->allows($user, 'View', 'BankAccount');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'Create', 'BankAccount');
    }

    public function update(User $user, BankAccount $bankAccount): bool
    {
        return $this->allows($user, 'Update', 'BankAccount');
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return $this->allows($user, 'Delete', 'BankAccount');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allows($user, 'DeleteAny', 'BankAccount');
    }
}
