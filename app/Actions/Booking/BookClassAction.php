<?php

declare(strict_types=1);

namespace App\Actions\Booking;

use App\Models\ClassBooking;
use App\Models\FitnessClass;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class BookClassAction
{
    public function execute(Member $member, int $classId): ClassBooking
    {
        return DB::transaction(function () use ($member, $classId): ClassBooking {
            if ($member->activeMembership() === null) {
                throw ValidationException::withMessages(['membership' => 'Active membership is required to book a class.']);
            }

            /** @var FitnessClass $class */
            $class = FitnessClass::query()->lockForUpdate()->findOrFail($classId);
            $bookedCount = $class->bookings()->where('status', 'confirmed')->count();

            if ($bookedCount >= $class->capacity) {
                throw ValidationException::withMessages(['class_id' => 'Class capacity is full.']);
            }

            $existing = ClassBooking::query()
                ->where('member_id', $member->id)
                ->where('fitness_class_id', $class->id)
                ->first();

            if ($existing !== null) {
                if ($existing->status === 'confirmed') {
                    return $existing;
                }

                $existing->update(['status' => 'confirmed', 'booked_at' => now(), 'cancelled_at' => null]);

                return $existing;
            }

            return ClassBooking::query()->create([
                'member_id' => $member->id,
                'fitness_class_id' => $class->id,
                'status' => 'confirmed',
                'booked_at' => now(),
            ]);
        });
    }
}
