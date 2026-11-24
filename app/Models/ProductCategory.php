<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::deleting(function (ProductCategory $category): void {
            $count = $category->products()->count();

            if ($count > 0) {
                throw new \DomainException(
                    "Kategori '{$category->name}' tidak dapat dihapus karena masih memiliki {$count} produk terdaftar. Silakan hapus atau pindahkan produk tersebut terlebih dahulu."
                );
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
