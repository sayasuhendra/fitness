<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksAdminPermissions
{
    private function allows(User $user, string $ability, string $model): bool
    {
        if ($user->hasAnyRole(['Owner', 'Super admin'])) {
            return true;
        }

        return $user->can("{$ability}:{$model}");
    }
}
