<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Member> */
class MemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'member_code' => 'MBR'.$this->faker->unique()->numerify('######'),
            'joined_at' => now()->toDateString(),
        ];
    }
}
