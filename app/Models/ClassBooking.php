<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ClassBookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassBooking extends Model
{
    /** @use HasFactory<ClassBookingFactory> */
    use HasFactory;

    protected $fillable = ['member_id', 'fitness_class_id', 'status', 'booked_at', 'cancelled_at'];

    protected function casts(): array
    {
        return ['booked_at' => 'datetime', 'cancelled_at' => 'datetime'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function fitnessClass(): BelongsTo
    {
        return $this->belongsTo(FitnessClass::class);
    }
}
