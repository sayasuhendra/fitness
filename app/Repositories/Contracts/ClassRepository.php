<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Member;
use Illuminate\Support\Collection;

interface ClassRepository
{
    public function schedules(?string $date = null): Collection;

    public function bookingsFor(Member $member): Collection;
}
