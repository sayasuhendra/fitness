<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PersonalTrainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PersonalTrainer> */
class PersonalTrainerFactory extends Factory
{
    protected $model = PersonalTrainer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialization' => $this->faker->randomElement(['Strength Training', 'Fat Loss', 'Body Shaping']),
            'whatsapp_number' => '628111513335',
            'bio' => $this->faker->paragraph(),
            'is_active' => true,
        ];
    }
}
