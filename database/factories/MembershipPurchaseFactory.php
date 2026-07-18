<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<MembershipPurchase> */
class MembershipPurchaseFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()->subDays(3);

        return [
            'member_id' => Member::factory(),
            'membership_package_id' => MembershipPackage::factory(),
            'starts_at' => $startsAt,
            'expires_at' => $startsAt->copy()->addDays(30),
            'status' => 'active',
            'includes_personal_trainer' => false,
            'visits_allowed' => null,
            'visits_used' => 0,
            'payment_method' => 'manual_transfer',
            'amount' => 350000,
            'payment_reference' => 'MANUAL-'.Str::upper(Str::random(12)),
        ];
    }
}
