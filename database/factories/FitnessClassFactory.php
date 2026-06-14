<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FitnessClass;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<FitnessClass> */
class FitnessClassFactory extends Factory
{
    public function definition(): array
    {
        return [
            'trainer_id' => Trainer::factory(),
            'name' => $this->faker->randomElement(['Morning Pilates', 'Akhwat Strength', 'Yoga Flow', 'HIIT Beginner']),
            'description' => $this->faker->sentence(),
            'capacity' => 12,
            'location' => 'Studio A',
            'class_date' => now()->addDays($this->faker->numberBetween(1, 7))->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'is_active' => true,
        ];
    }
}
