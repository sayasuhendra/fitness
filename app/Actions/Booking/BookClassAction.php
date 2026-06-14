<?php

declare(strict_types=1);

namespace App\Actions\Booking;

use App\Models\ClassBooking;
use App\Models\FitnessClass;
use App\Models\Member;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class BookClassAction
{
    public function execute(
        Member $member,
        int $classId,
        ?string $bookedForDate = null,
        string $accessType = 'membership',
        bool $personalTrainerRequested = false,
        ?string $paymentMethod = null,
    ): ClassBooking {
        return DB::transaction(function () use ($member, $classId, $bookedForDate, $accessType, $personalTrainerRequested, $paymentMethod): ClassBooking {
            /** @var FitnessClass $class */
            $class = FitnessClass::query()->lockForUpdate()->findOrFail($classId);
            $bookingDate = Carbon::parse($bookedForDate ?? $class->class_date)->toDateString();
            $accessType = $accessType === 'one_time' ? 'one_time' : 'membership';

            if (! $class->occursOn($bookingDate)) {
                throw ValidationException::withMessages(['booked_for_date' => 'This class is not available on the selected date.']);
            }

            $membership = $member->activeMembership();

            if ($accessType === 'membership') {
                if ($membership === null) {
                    throw ValidationException::withMessages(['membership' => 'Active membership is required to book a class.']);
                }

                if (! $membership->hasRemainingVisits()) {
                    throw ValidationException::withMessages(['membership' => 'Your membership visit limit has been used.']);
                }
            }

            if ($accessType === 'one_time' && ! $class->allow_drop_in) {
                throw ValidationException::withMessages(['access_type' => 'This class does not accept one-time visitors.']);
            }

            if ($personalTrainerRequested && $accessType === 'membership' && ! $membership?->includes_personal_trainer) {
                throw ValidationException::withMessages(['personal_trainer_requested' => 'Your membership does not include a personal trainer.']);
            }

            $bookedCount = $class->confirmedBookingsCountForDate($bookingDate);

            if ($bookedCount >= $class->capacity) {
                throw ValidationException::withMessages(['class_id' => 'Class capacity is full.']);
            }

            $existing = ClassBooking::query()
                ->where('member_id', $member->id)
                ->where('fitness_class_id', $class->id)
                ->whereDate('booked_for_date', $bookingDate)
                ->first();

            if ($existing !== null) {
                if ($existing->status === 'confirmed') {
                    return $existing;
                }

                $existing->update([
                    'status' => 'confirmed',
                    'access_type' => $accessType,
                    'personal_trainer_requested' => $personalTrainerRequested,
                    'amount' => $this->bookingAmount($class, $accessType, $personalTrainerRequested),
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $accessType === 'one_time' ? 'VISIT-'.Str::upper(Str::random(10)) : null,
                    'booked_at' => now(),
                    'cancelled_at' => null,
                ]);

                if ($accessType === 'membership' && $membership !== null) {
                    $membership->increment('visits_used');
                }

                return $existing;
            }

            $booking = ClassBooking::query()->create([
                'member_id' => $member->id,
                'fitness_class_id' => $class->id,
                'booked_for_date' => $bookingDate,
                'status' => 'confirmed',
                'access_type' => $accessType,
                'personal_trainer_requested' => $personalTrainerRequested,
                'amount' => $this->bookingAmount($class, $accessType, $personalTrainerRequested),
                'payment_method' => $paymentMethod,
                'payment_reference' => $accessType === 'one_time' ? 'VISIT-'.Str::upper(Str::random(10)) : null,
                'booked_at' => now(),
            ]);

            if ($accessType === 'membership' && $membership !== null) {
                $membership->increment('visits_used');
            }

            return $booking;
        });
    }

    private function bookingAmount(FitnessClass $class, string $accessType, bool $personalTrainerRequested): float
    {
        if ($accessType === 'membership') {
            return 0;
        }

        return (float) $class->drop_in_price + ($personalTrainerRequested ? (float) $class->trainer_addon_price : 0);
    }
}
