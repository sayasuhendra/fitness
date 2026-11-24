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
        'allowed_class_types',
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
            'allowed_class_types' => 'array',
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (MembershipPackage $package): void {
            $count = $package->purchases()->count();

            if ($count > 0) {
                throw new \DomainException(
                    "Paket membership '{$package->name}' tidak dapat dihapus karena masih digunakan oleh {$count} data transaksi / member terdaftar. Silakan nonaktifkan status paket agar tidak dapat dipilih lagi."
                );
            }
        });
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

    public function allowsClassType(string $classType): bool
    {
        if ($this->allowed_class_types === null || $this->allowed_class_types === []) {
            return true;
        }

        if (in_array($classType, $this->allowed_class_types, true)) {
            return true;
        }

        // If class is general, allow it if package includes general class types (not just gym or pt)
        if ($classType === 'general' && count(array_diff($this->allowed_class_types, ['gym', 'personal_trainer', 'body_fat'])) > 0) {
            return true;
        }

        return false;
    }
}
