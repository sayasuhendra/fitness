<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSession extends Model
{
    protected $fillable = [
        'fitness_class_id',
        'trainer_id',
        'session_date',
        'start_time',
        'end_time',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
        ];
    }

    public function fitnessClass(): BelongsTo
    {
        return $this->belongsTo(FitnessClass::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ClassBooking::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function confirmedBookingsCount(): int
    {
        return $this->bookings()->where('status', 'confirmed')->count();
    }
}
