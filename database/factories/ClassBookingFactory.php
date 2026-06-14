<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ClassBooking;
use App\Models\FitnessClass;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ClassBooking> */
class ClassBookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'fitness_class_id' => FitnessClass::factory(),
            'booked_for_date' => now()->toDateString(),
            'status' => 'confirmed',
            'access_type' => 'membership',
            'personal_trainer_requested' => false,
            'amount' => 0,
            'booked_at' => now(),
        ];
    }
}
