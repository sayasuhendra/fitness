<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Member;
use Illuminate\Support\Collection;

interface MembershipRepository
{
    public function activePackages(): Collection;

    public function historyFor(Member $member): Collection;
}
