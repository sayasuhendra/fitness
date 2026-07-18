<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ClassBookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ClassBooking extends Model
{
    /** @use HasFactory<ClassBookingFactory> */
    use HasFactory;

    protected $fillable = [
        'member_id',
        'fitness_class_id',
        'class_session_id',
        'booked_for_date',
        'status',
        'access_type',
        'personal_trainer_requested',
        'amount',
        'payment_method',
        'payment_reference',
        'booked_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'booked_for_date' => 'date',
            'personal_trainer_requested' => 'boolean',
            'amount' => 'decimal:2',
            'booked_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function fitnessClass(): BelongsTo
    {
        return $this->belongsTo(FitnessClass::class);
    }

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function paymentConfirmations(): MorphMany
    {
        return $this->morphMany(PaymentConfirmation::class, 'payable');
    }
}
