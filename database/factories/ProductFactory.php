<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_category_id' => ProductCategory::query()->first()?->id ?? ProductCategory::query()->create([
                'name' => 'Healthy Food',
                'slug' => 'healthy-food',
            ])->id,
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(25000, 250000),
            'stock' => $this->faker->numberBetween(5, 100),
            'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80',
            'is_active' => true,
        ];
    }
}
