<?php

declare(strict_types=1);

namespace App\Actions\Booking;

use App\Models\ClassBooking;
use App\Models\ClassSession;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Services\Classes\ClassSessionGenerator;
use App\Services\Notifications\MemberNotificationService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class BookClassAction
{
    public function execute(
        Member $member,
        int $classId,
        ?int $classSessionId = null,
        ?string $bookedForDate = null,
        string $accessType = 'membership',
        bool $personalTrainerRequested = false,
        ?string $paymentMethod = null,
    ): ClassBooking {
        $booking = DB::transaction(function () use ($member, $classId, $classSessionId, $bookedForDate, $accessType, $personalTrainerRequested, $paymentMethod): ClassBooking {
            /** @var FitnessClass $class */
            $class = FitnessClass::query()->lockForUpdate()->findOrFail($classId);
            $session = $this->resolveSession($class, $classSessionId, $bookedForDate);
            $bookingDate = $session->session_date->toDateString();
            $accessType = $accessType === 'one_time' ? 'one_time' : 'membership';

            if ($personalTrainerRequested) {
                throw ValidationException::withMessages([
                    'personal_trainer_requested' => 'Personal trainer hanya bisa dipesan melalui menu Personal Trainer.',
                ]);
            }

            if ($session->fitness_class_id !== $class->id) {
                throw ValidationException::withMessages(['class_session_id' => 'This session does not belong to the selected class.']);
            }

            $membership = $member->activeMembership();

            if ($accessType === 'membership') {
                if ($membership === null) {
                    throw ValidationException::withMessages(['membership' => 'Active membership is required to book a class.']);
                }

                if (! $membership->hasRemainingVisits()) {
                    throw ValidationException::withMessages(['membership' => 'Your membership visit limit has been used.']);
                }

                if (! $membership->package->allowsClassType($class->class_type)) {
                    throw ValidationException::withMessages(['membership' => 'Your membership package does not include this class type.']);
                }
            }

            if ($accessType === 'one_time' && ! $class->allow_drop_in) {
                throw ValidationException::withMessages(['access_type' => 'This class does not accept one-time visitors.']);
            }

            $bookedCount = $session->confirmedBookingsCount();

            if ($bookedCount >= $session->capacity) {
                throw ValidationException::withMessages(['class_id' => 'Class capacity is full.']);
            }

            $existing = ClassBooking::query()
                ->where('member_id', $member->id)
                ->where('class_session_id', $session->id)
                ->first();

            if ($existing !== null) {
                if ($existing->status === 'confirmed') {
                    return $existing;
                }

                $existing->update([
                    'status' => $accessType === 'one_time' ? 'pending_payment' : 'confirmed',
                    'access_type' => $accessType,
                    'class_session_id' => $session->id,
                    'personal_trainer_requested' => $personalTrainerRequested,
                    'amount' => $this->bookingAmount($class, $accessType, $personalTrainerRequested),
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $accessType === 'one_time' ? 'MANUAL-VISIT-'.Str::upper(Str::random(10)) : null,
                    'booked_at' => now(),
                    'cancelled_at' => null,
                ]);

                return $existing;
            }

            $booking = ClassBooking::query()->create([
                'member_id' => $member->id,
                'fitness_class_id' => $class->id,
                'class_session_id' => $session->id,
                'booked_for_date' => $bookingDate,
                'status' => $accessType === 'one_time' ? 'pending_payment' : 'confirmed',
                'access_type' => $accessType,
                'personal_trainer_requested' => $personalTrainerRequested,
                'amount' => $this->bookingAmount($class, $accessType, $personalTrainerRequested),
                'payment_method' => $paymentMethod,
                'payment_reference' => $accessType === 'one_time' ? 'MANUAL-VISIT-'.Str::upper(Str::random(10)) : null,
                'booked_at' => now(),
            ]);

            return $booking;
        });

        $this->notifyMember($booking);

        return $booking;
    }

    private function bookingAmount(FitnessClass $class, string $accessType, bool $personalTrainerRequested): float
    {
        if ($accessType === 'membership') {
            return 0;
        }

        return (float) $class->drop_in_price;
    }

    private function resolveSession(FitnessClass $class, ?int $classSessionId, ?string $bookedForDate): ClassSession
    {
        if ($classSessionId !== null) {
            return ClassSession::query()->lockForUpdate()->findOrFail($classSessionId);
        }

        $bookingDate = Carbon::parse($bookedForDate ?? $class->class_date)->toDateString();

        if (! $class->occursOn($bookingDate)) {
            throw ValidationException::withMessages(['booked_for_date' => 'This class is not available on the selected date.']);
        }

        app(ClassSessionGenerator::class)->forDate($bookingDate);

        return ClassSession::query()
            ->where('fitness_class_id', $class->id)
            ->whereDate('session_date', $bookingDate)
            ->where('start_time', $class->start_time)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function notifyMember(ClassBooking $booking): void
    {
        $booking->loadMissing('member.user', 'fitnessClass');
        $user = $booking->member?->user;

        if ($user === null) {
            return;
        }

        if ($booking->status === 'pending_payment') {
            app(MemberNotificationService::class)->send(
                $user,
                'Menunggu konfirmasi pembayaran',
                'Booking '.$booking->fitnessClass->name.' sudah dibuat. Silakan selesaikan pembayaran agar jadwal terkonfirmasi.',
                'booking_pending_payment',
                '/payments/manual?payable_type=class_booking&payable_id='.$booking->id.'&amount='.$booking->amount.'&payment_method='.($booking->payment_method ?? 'qris'),
            );

            return;
        }

        app(MemberNotificationService::class)->send(
            $user,
            'Booking kelas berhasil',
            'Anda berhasil booking '.$booking->fitnessClass->name.'. Sampai jumpa di kelas.',
            'booking_confirmed',
            '/classes/my-bookings',
        );
    }
}
