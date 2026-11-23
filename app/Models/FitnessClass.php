<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FitnessClassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class FitnessClass extends Model
{
    /** @use HasFactory<FitnessClassFactory> */
    use HasFactory;
    use SoftDeletes;

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

    protected static function booted(): void
    {
        static::deleting(function (FitnessClass $class): void {
            if (! $class->isForceDeleting()) {
                $class->sessions()->each(fn (ClassSession $session) => $session->delete());
            }
        });

        static::restoring(function (FitnessClass $class): void {
            $class->sessions()->onlyTrashed()->each(fn (ClassSession $session) => $session->restore());
        });

        static::saved(function (FitnessClass $class): void {
            $class->sessions()
                ->where('status', 'scheduled')
                ->whereDate('session_date', '>=', now()->toDateString())
                ->each(function (ClassSession $session) use ($class): void {
                    if (! $class->is_active || ! $class->occursOn($session->session_date->toDateString())) {
                        if ($session->bookings()->count() === 0) {
                            $session->delete();
                        }
                    } else {
                        $session->update([
                            'trainer_id' => $class->trainer_id,
                            'start_time' => $class->start_time,
                            'end_time' => $class->end_time,
                            'capacity' => $class->capacity,
                        ]);
                    }
                });
        });
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class)->withTrashed();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ClassBooking::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
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

        if (! $this->is_recurring) {
            return $this->class_date->isSameDay($target);
        }

        if ($target->lessThan($this->class_date->startOfDay())) {
            return false;
        }

        if ($this->recurrence_ends_at !== null && $target->greaterThan($this->recurrence_ends_at->startOfDay())) {
            return false;
        }

        $days = array_map('strtolower', $this->recurring_days ?? []);

        return in_array(strtolower($target->englishDayOfWeek), $days, true);
    }
}
