<?php

declare(strict_types=1);

namespace App\Services\Classes;

use App\Models\ClassSession;
use App\Models\FitnessClass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class ClassSessionGenerator
{
    /**
     * @return Collection<int, ClassSession>
     */
    public function forDate(Carbon|string $date): Collection
    {
        $targetDate = Carbon::parse($date)->toDateString();

        $classes = FitnessClass::query()
            ->where('is_active', true)
            ->with('trainer.user')
            ->get()
            ->filter(fn (FitnessClass $class): bool => $class->occursOn($targetDate));

        foreach ($classes as $class) {
            ClassSession::query()->firstOrCreate([
                'fitness_class_id' => $class->id,
                'session_date' => $targetDate,
                'start_time' => $class->start_time,
            ], [
                'trainer_id' => $class->trainer_id,
                'end_time' => $class->end_time,
                'capacity' => $class->capacity,
                'status' => 'scheduled',
            ]);
        }

        return ClassSession::query()
            ->with(['fitnessClass.trainer.user'])
            ->withCount(['bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->whereDate('session_date', $targetDate)
            ->where('status', 'scheduled')
            ->orderBy('start_time')
            ->get();
    }
}
