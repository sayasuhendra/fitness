<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Booking\BookClassAction;
use App\Actions\Booking\CancelBookingAction;
use App\Http\Requests\Booking\BookClassRequest;
use App\Http\Requests\Booking\CancelBookingRequest;
use App\Http\Resources\ClassBookingResource;
use App\Http\Resources\ClassSessionResource;
use App\Http\Resources\FitnessClassResource;
use App\Models\FitnessClass;
use App\Services\ApiResponder;
use App\Services\Classes\ClassSessionGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request, ClassSessionGenerator $sessionGenerator): JsonResponse
    {
        $date = $request->query('date');

        if (is_string($date)) {
            $sessions = $sessionGenerator->forDate($date);

            return ApiResponder::success(ClassSessionResource::collection($sessions), 'Class sessions retrieved');
        }

        $classes = FitnessClass::query()
            ->with('trainer.user')
            ->withCount(['bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->where('is_active', true)
            ->when($date, fn ($query, string $value) => $query->whereDate('class_date', '<=', $value))
            ->orderBy('class_date')
            ->orderBy('start_time')
            ->get();

        return ApiResponder::success(FitnessClassResource::collection($classes), 'Classes retrieved');
    }

    public function book(BookClassRequest $request, BookClassAction $action): JsonResponse
    {
        $booking = $action->execute(
            member: $request->user()->member,
            classId: (int) $request->validated('class_id'),
            classSessionId: $request->validated('class_session_id') !== null ? (int) $request->validated('class_session_id') : null,
            bookedForDate: $request->validated('booked_for_date') ?? null,
            accessType: $request->validated('access_type') ?? 'membership',
            personalTrainerRequested: (bool) ($request->validated('personal_trainer_requested') ?? false),
            paymentMethod: $request->validated('payment_method') ?? null,
        );

        return ApiResponder::success(new ClassBookingResource($booking->load(['fitnessClass.trainer.user', 'paymentConfirmations'])), 'Class booked', 201);
    }

    public function cancel(CancelBookingRequest $request, CancelBookingAction $action): JsonResponse
    {
        $booking = $action->execute($request->user()->member, (int) $request->validated('booking_id'));

        return ApiResponder::success(new ClassBookingResource($booking->load('fitnessClass.trainer.user')), 'Booking cancelled');
    }

    public function myBookings(Request $request): JsonResponse
    {
        $bookings = $request->user()->member->bookings()
            ->with(['fitnessClass.trainer.user', 'paymentConfirmations'])
            ->latest('booked_at')
            ->get();

        return ApiResponder::success(ClassBookingResource::collection($bookings), 'Bookings retrieved');
    }
}
