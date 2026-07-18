<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use ChecksAdminPermissions, HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'ViewAny', 'Attendance');
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $this->allows($user, 'View', 'Attendance');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'Create', 'Attendance');
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $this->allows($user, 'Update', 'Attendance');
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $this->allows($user, 'Delete', 'Attendance');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allows($user, 'DeleteAny', 'Attendance');
    }
}
