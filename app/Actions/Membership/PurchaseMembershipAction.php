<?php

declare(strict_types=1);

namespace App\Actions\Membership;

use App\DTO\PurchaseMembershipData;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class PurchaseMembershipAction
{
    public function execute(Member $member, PurchaseMembershipData $data): MembershipPurchase
    {
        return DB::transaction(function () use ($member, $data): MembershipPurchase {
            $package = MembershipPackage::query()->where('is_active', true)->findOrFail($data->packageId);
            $startsAt = now();

            return MembershipPurchase::query()->create([
                'member_id' => $member->id,
                'membership_package_id' => $package->id,
                'starts_at' => $startsAt,
                'expires_at' => $startsAt->copy()->addDays($package->duration_days),
                'status' => 'active',
                'payment_method' => $data->paymentMethod,
                'amount' => $package->price,
                'payment_reference' => 'MID-'.Str::upper(Str::random(12)),
            ]);
        });
    }
}
