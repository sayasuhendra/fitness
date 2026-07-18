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
            'includes_personal_trainer' => 'boolean',
            'amount' => 'decimal:2',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(MembershipPackage::class, 'membership_package_id');
    }

    public function paymentConfirmations(): MorphMany
    {
        return $this->morphMany(PaymentConfirmation::class, 'payable');
    }

    public function hasRemainingVisits(): bool
    {
        return $this->visits_allowed === null || $this->visits_used < $this->visits_allowed;
    }

    public function remainingVisits(): ?int
    {
        if ($this->visits_allowed === null) {
            return null;
        }

        return max(0, $this->visits_allowed - $this->visits_used);
    }
}
