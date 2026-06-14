<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\FitnessClass;
use App\Models\Member;
use App\Repositories\Contracts\ClassRepository;
use Illuminate\Support\Collection;

class EloquentClassRepository implements ClassRepository
{
    public function schedules(?string $date = null): Collection
    {
        return FitnessClass::query()
            ->with('trainer.user')
            ->withCount(['bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->where('is_active', true)
            ->when($date, fn ($query, string $value) => $query->whereDate('class_date', $value))
            ->orderBy('class_date')
            ->orderBy('start_time')
            ->get();
    }

    public function bookingsFor(Member $member): Collection
    {
        return $member->bookings()->with('fitnessClass.trainer.user')->latest('booked_at')->get();
    }
}
