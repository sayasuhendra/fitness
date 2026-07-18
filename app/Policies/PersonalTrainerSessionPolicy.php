<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PersonalTrainerSession;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalTrainerSessionPolicy
{
    use ChecksAdminPermissions, HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'ViewAny', 'PersonalTrainerSession');
    }

    public function view(User $user, PersonalTrainerSession $personalTrainerSession): bool
    {
        return $this->allows($user, 'View', 'PersonalTrainerSession');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'Create', 'PersonalTrainerSession');
    }

    public function update(User $user, PersonalTrainerSession $personalTrainerSession): bool
    {
        return $this->allows($user, 'Update', 'PersonalTrainerSession');
    }

    public function delete(User $user, PersonalTrainerSession $personalTrainerSession): bool
    {
        return $this->allows($user, 'Delete', 'PersonalTrainerSession');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allows($user, 'DeleteAny', 'PersonalTrainerSession');
    }
}
