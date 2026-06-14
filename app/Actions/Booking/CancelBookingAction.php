<?php

declare(strict_types=1);

namespace App\Actions\Booking;

use App\Models\ClassBooking;
use App\Models\Member;
use Illuminate\Validation\ValidationException;

final class CancelBookingAction
{
    public function execute(Member $member, int $bookingId): ClassBooking
    {
        /** @var ClassBooking $booking */
        $booking = ClassBooking::query()->where('member_id', $member->id)->findOrFail($bookingId);

        if ($booking->status === 'cancelled') {
            throw ValidationException::withMessages(['booking_id' => 'Booking has already been cancelled.']);
        }

        $booking->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return $booking;
    }
}
