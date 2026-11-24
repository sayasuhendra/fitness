<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MembershipPurchaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MembershipPurchase extends Model
{
    /** @use HasFactory<MembershipPurchaseFactory> */
    use HasFactory;

    protected $fillable = [
        'member_id',
        'handled_by',
        'handled_shift',
        'handled_date',
        'membership_package_id',
        'starts_at',
        'expires_at',
        'status',
        'includes_personal_trainer',
        'visits_allowed',
        'visits_used',
        'payment_method',
        'amount',
        'payment_reference',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'handled_date' => 'date',
            'includes_personal_trainer' => 'boolean',
            'amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (MembershipPurchase $purchase): void {
            if ($purchase->status === 'active') {
                if ($purchase->starts_at === null) {
                    $purchase->starts_at = now();
                }

                $package = $purchase->package ?? MembershipPackage::find($purchase->membership_package_id);

                if ($package !== null) {
                    if ($purchase->expires_at === null) {
                        $purchase->expires_at = $purchase->starts_at->copy()->addDays($package->duration_days);
                    }
                    if ($purchase->visits_allowed === null && $package->has_visit_limit) {
                        $purchase->visits_allowed = $package->visit_limit;
                    }
                    if (($purchase->amount === null || (float) $purchase->amount <= 0) && (float) $package->price > 0) {
                        $purchase->amount = $package->price;
                    }
                    if (! $purchase->includes_personal_trainer && $package->includes_personal_trainer) {
                        $purchase->includes_personal_trainer = true;
                    }
                }
            }
        });
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(MembershipPackage::class, 'membership_package_id');
    }

    public function paymentConfirmations(): MorphMany
    {
        return $this->morphMany(PaymentConfirmation::class, 'payable');
    }

    public function hasRemainingVisits(int $units = 1): bool
    {
        return $this->visits_allowed === null || ($this->visits_used + $units) <= $this->visits_allowed;
    }

    public function remainingVisits(): ?int
    {
        if ($this->visits_allowed === null) {
            return null;
        }

        return max(0, $this->visits_allowed - $this->visits_used);
    }
}
