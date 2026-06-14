<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Member;
use App\Models\MembershipPackage;
use App\Repositories\Contracts\MembershipRepository;
use Illuminate\Support\Collection;

class EloquentMembershipRepository implements MembershipRepository
{
    public function activePackages(): Collection
    {
        return MembershipPackage::query()->where('is_active', true)->oldest('price')->get();
    }

    public function historyFor(Member $member): Collection
    {
        return $member->membershipPurchases()->with('package')->latest()->get();
    }
}
