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
            $durationDays = $data->billingCycle === 'yearly' && $package->billing_cycle === 'monthly'
                ? 365
                : $package->duration_days;

            return MembershipPurchase::query()->create([
                'member_id' => $member->id,
                'membership_package_id' => $package->id,
                'starts_at' => $startsAt,
                'expires_at' => $startsAt->copy()->addDays($durationDays),
                'status' => 'active',
                'includes_personal_trainer' => $package->includes_personal_trainer,
                'visits_allowed' => $package->has_visit_limit ? $package->visit_limit : null,
                'visits_used' => 0,
                'payment_method' => $data->paymentMethod,
                'amount' => $package->price,
                'payment_reference' => 'MID-'.Str::upper(Str::random(12)),
            ]);
        });
    }
}
