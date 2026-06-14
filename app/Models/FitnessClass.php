<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FitnessClassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FitnessClass extends Model
{
    /** @use HasFactory<FitnessClassFactory> */
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'name',
        'description',
        'capacity',
        'location',
        'class_date',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['class_date' => 'date', 'is_active' => 'boolean'];
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
