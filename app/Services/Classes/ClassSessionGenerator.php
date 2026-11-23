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

        $activeClassIds = $classes->pluck('id')->all();

        ClassSession::query()
            ->whereDate('session_date', $targetDate)
            ->where('status', 'scheduled')
            ->whereNotIn('fitness_class_id', $activeClassIds)
            ->whereDoesntHave('bookings')
            ->delete();

        foreach ($classes as $class) {
            $existingSession = ClassSession::query()
                ->where('fitness_class_id', $class->id)
                ->whereDate('session_date', $targetDate)
                ->where('status', 'scheduled')
                ->first();

            if ($existingSession !== null) {
                $existingSession->update([
                    'trainer_id' => $class->trainer_id,
                    'start_time' => $class->start_time,
                    'end_time' => $class->end_time,
                    'capacity' => $class->capacity,
                ]);
            } else {
                ClassSession::query()->create([
                    'fitness_class_id' => $class->id,
                    'session_date' => $targetDate,
                    'start_time' => $class->start_time,
                    'trainer_id' => $class->trainer_id,
                    'end_time' => $class->end_time,
                    'capacity' => $class->capacity,
                    'status' => 'scheduled',
                ]);
            }
        }

        return ClassSession::query()
            ->with(['fitnessClass.trainer.user'])
            ->withCount(['bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->whereDate('session_date', $targetDate)
            ->whereIn('fitness_class_id', $activeClassIds)
            ->where('status', 'scheduled')
            ->orderBy('start_time')
            ->get();
    }
}
