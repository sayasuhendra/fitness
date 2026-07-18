<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PersonalTrainerSession extends Model
{
    protected $fillable = [
        'member_id',
        'trainer_id',
        'membership_purchase_id',
        'scheduled_at',
        'duration_minutes',
        'status',
        'access_type',
        'amount',
        'payment_method',
        'payment_reference',
        'completed_at',
        'member_note',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function membershipPurchase(): BelongsTo
    {
        return $this->belongsTo(MembershipPurchase::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function paymentConfirmations(): MorphMany
    {
        return $this->morphMany(PaymentConfirmation::class, 'payable');
    }
}
