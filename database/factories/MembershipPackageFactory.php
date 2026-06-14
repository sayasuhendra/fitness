<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MembershipPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MembershipPackage> */
class MembershipPackageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Starter', 'Active', 'Premium']),
            'description' => $this->faker->sentence(),
            'package_type' => 'membership',
            'billing_cycle' => $this->faker->randomElement(['monthly', 'yearly']),
            'includes_personal_trainer' => false,
            'has_visit_limit' => false,
            'visit_limit' => null,
            'duration_days' => $this->faker->randomElement([30, 90, 365]),
            'price' => $this->faker->numberBetween(150000, 2500000),
            'discount_percent' => 0,
            'original_price' => null,
            'is_active' => true,
        ];
    }
}
