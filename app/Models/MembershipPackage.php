<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MembershipPackageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPackage extends Model
{
    /** @use HasFactory<MembershipPackageFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'duration_days', 'price', 'is_active'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(MembershipPurchase::class);
    }
}
