<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PersonalTrainer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PersonalTrainerPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['Owner', 'Super admin', 'Admin di lokasi']);
    }

    public function view(AuthUser $authUser, PersonalTrainer $personalTrainer): bool
    {
        return $this->viewAny($authUser);
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function update(AuthUser $authUser, PersonalTrainer $personalTrainer): bool
    {
        return $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function delete(AuthUser $authUser, PersonalTrainer $personalTrainer): bool
    {
        return $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['Owner', 'Super admin']);
    }
}
