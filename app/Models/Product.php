<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'name',
        'description',
        'price',
        'cost_price',
        'stock',
        'image_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'cost_price' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function marginAmount(): float
    {
        return (float) $this->price - (float) $this->cost_price;
    }

    public function marginPercentage(): float
    {
        if ((float) $this->price <= 0.0) {
            return 0.0;
        }

        return ($this->marginAmount() / (float) $this->price) * 100;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
