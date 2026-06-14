<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FitnessClassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class FitnessClass extends Model
{
    /** @use HasFactory<FitnessClassFactory> */
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'name',
        'class_type',
        'description',
        'capacity',
        'location',
        'is_recurring',
        'recurring_days',
        'recurrence_ends_at',
        'class_date',
        'start_time',
        'end_time',
        'is_active',
        'allow_drop_in',
        'drop_in_price',
        'trainer_addon_price',
    ];

    protected function casts(): array
    {
        return [
            'class_date' => 'date',
            'is_recurring' => 'boolean',
            'recurring_days' => 'array',
            'recurrence_ends_at' => 'date',
            'is_active' => 'boolean',
            'allow_drop_in' => 'boolean',
            'drop_in_price' => 'decimal:2',
            'trainer_addon_price' => 'decimal:2',
        ];
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

    public function confirmedBookingsCountForDate(string $date): int
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->whereDate('booked_for_date', $date)
            ->count();
    }

    public function occursOn(string $date): bool
    {
        $target = Carbon::parse($date)->startOfDay();

        if ($this->class_date->isSameDay($target)) {
            return true;
        }

        if (! $this->is_recurring || $target->lessThan($this->class_date)) {
            return false;
        }

        if ($this->recurrence_ends_at !== null && $target->greaterThan($this->recurrence_ends_at)) {
            return false;
        }

        return in_array(strtolower($target->englishDayOfWeek), $this->recurring_days ?? [], true);
    }
}
