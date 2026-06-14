<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Member;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Order> */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'status' => 'completed',
            'payment_method' => 'midtrans',
            'total_price' => 0,
            'payment_reference' => 'MID-'.Str::upper(Str::random(12)),
        ];
    }
}
