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

    protected $fillable = [
        'name',
        'description',
        'package_type',
        'billing_cycle',
        'includes_personal_trainer',
        'has_visit_limit',
        'visit_limit',
        'duration_days',
        'price',
        'discount_percent',
        'original_price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'includes_personal_trainer' => 'boolean',
            'has_visit_limit' => 'boolean',
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(MembershipPurchase::class);
    }

    public function visitLabel(): string
    {
        if (! $this->has_visit_limit) {
            return 'Unlimited visit';
        }

        return "{$this->visit_limit} visit";
    }
}
