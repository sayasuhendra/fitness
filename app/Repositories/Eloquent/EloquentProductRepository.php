<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepository;
use Illuminate\Support\Collection;

class EloquentProductRepository implements ProductRepository
{
    public function search(?string $category = null, ?string $search = null): Collection
    {
        return Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($category, fn ($query, string $value) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $value)))
            ->when($search, fn ($query, string $value) => $query->where('name', 'like', "%{$value}%"))
            ->latest()
            ->get();
    }
}
