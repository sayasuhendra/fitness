<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'member_id',
        'handled_by',
        'handled_shift',
        'handled_date',
        'fitness_class_id',
        'class_session_id',
        'class_booking_id',
        'personal_trainer_session_id',
        'membership_purchase_id',
        'attendance_type',
        'check_in_time',
        'status',
        'location',
    ];

    protected function casts(): array
    {
        return ['handled_date' => 'date', 'check_in_time' => 'datetime'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function fitnessClass(): BelongsTo
    {
        return $this->belongsTo(FitnessClass::class);
    }

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function classBooking(): BelongsTo
    {
        return $this->belongsTo(ClassBooking::class);
    }

    public function membershipPurchase(): BelongsTo
    {
        return $this->belongsTo(MembershipPurchase::class);
    }

    public function personalTrainerSession(): BelongsTo
    {
        return $this->belongsTo(PersonalTrainerSession::class);
    }
}
