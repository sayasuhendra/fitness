<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Models\Attendance;
use App\Models\ClassBooking;
use App\Models\Member;
use App\Models\MembershipPurchase;
use App\Models\PersonalTrainerSession;
use App\Models\User;
use App\Support\AdminShift;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckInMemberAction
{
    public function execute(
        Member $member,
        string $attendanceType,
        ?ClassBooking $booking = null,
        ?PersonalTrainerSession $personalTrainerSession = null,
        ?string $location = null,
        ?User $admin = null,
    ): Attendance {
        return DB::transaction(function () use ($member, $attendanceType, $booking, $personalTrainerSession, $location, $admin): Attendance {
            $membership = $member->activeMembership();

            if ($booking !== null) {
                $booking = ClassBooking::query()->lockForUpdate()->findOrFail($booking->id);

                if ($booking->member_id !== $member->id) {
                    throw ValidationException::withMessages(['class_booking_id' => 'Booking tidak sesuai dengan member.']);
                }

                if ($booking->status !== 'confirmed') {
                    throw ValidationException::withMessages(['class_booking_id' => 'Booking belum valid untuk check-in.']);
                }

                if ($booking->access_type === 'membership') {
                    $membership = $this->validMembershipForCheckIn($member, $booking);
                }
            } elseif ($personalTrainerSession !== null) {
                $personalTrainerSession = PersonalTrainerSession::query()->lockForUpdate()->findOrFail($personalTrainerSession->id);

                if ($personalTrainerSession->member_id !== $member->id) {
                    throw ValidationException::withMessages(['personal_trainer_session_id' => 'Sesi PT tidak sesuai dengan member.']);
                }

                if ($personalTrainerSession->status !== 'scheduled') {
                    throw ValidationException::withMessages(['personal_trainer_session_id' => 'Sesi PT belum valid untuk check-in.']);
                }

                if ($personalTrainerSession->access_type === 'membership') {
                    $membership = $this->validPersonalTrainerMembership($member);
                }
            } else {
                $membership = $this->validMembershipForCheckIn($member);
            }

            $existing = Attendance::query()
                ->where('member_id', $member->id)
                ->when($booking, fn ($query) => $query->where('class_booking_id', $booking->id))
                ->when($personalTrainerSession, fn ($query) => $query->where('personal_trainer_session_id', $personalTrainerSession->id))
                ->whereDate('check_in_time', now()->toDateString())
                ->first();

            if ($existing !== null) {
                return $existing;
            }

            if ($membership instanceof MembershipPurchase) {
                if (! $membership->hasRemainingVisits()) {
                    throw ValidationException::withMessages(['membership' => 'Kuota kunjungan membership sudah habis.']);
                }

                if ($membership->visits_allowed !== null) {
                    $membership->increment('visits_used');
                }
            }

            return Attendance::query()->create([
                'member_id' => $member->id,
                ...AdminShift::stamp($admin),
                'fitness_class_id' => $booking?->fitness_class_id,
                'class_session_id' => $booking?->class_session_id,
                'class_booking_id' => $booking?->id,
                'personal_trainer_session_id' => $personalTrainerSession?->id,
                'membership_purchase_id' => $membership?->id,
                'attendance_type' => $attendanceType,
                'check_in_time' => now(),
                'status' => 'present',
                'location' => $location ?? 'Akhwat Gym Studio',
            ]);
        });
    }

    private function validMembershipForCheckIn(Member $member, ?ClassBooking $booking = null): MembershipPurchase
    {
        $membership = $member->activeMembership();

        if ($membership === null) {
            throw ValidationException::withMessages(['membership' => 'Member belum memiliki membership aktif.']);
        }

        if ($booking !== null && ! $membership->package->allowsClassType($booking->fitnessClass->class_type)) {
            throw ValidationException::withMessages(['membership' => 'Paket membership tidak mencakup kelas ini.']);
        }

        return $membership;
    }

    private function validPersonalTrainerMembership(Member $member): MembershipPurchase
    {
        $membership = $member->activeMembership();

        if ($membership === null || ! $membership->includes_personal_trainer) {
            throw ValidationException::withMessages(['membership' => 'Member belum memiliki paket personal trainer aktif.']);
        }

        return $membership;
    }
}
