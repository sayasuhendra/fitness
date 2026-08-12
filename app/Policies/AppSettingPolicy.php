<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AppSetting;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AppSettingPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['Owner', 'Super admin']);
    }

    public function view(AuthUser $authUser, AppSetting $appSetting): bool
    {
        return $this->viewAny($authUser);
    }

    public function update(AuthUser $authUser, AppSetting $appSetting): bool
    {
        return $this->viewAny($authUser);
    }
}
