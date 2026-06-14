<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Trainer> */
class TrainerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialization' => $this->faker->randomElement(['Pilates', 'Yoga', 'Strength', 'HIIT']),
            'bio' => $this->faker->paragraph(),
            'is_active' => true,
        ];
    }
}
