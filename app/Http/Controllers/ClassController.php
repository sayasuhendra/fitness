<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Booking\BookClassAction;
use App\Actions\Booking\CancelBookingAction;
use App\Http\Requests\Booking\BookClassRequest;
use App\Http\Requests\Booking\CancelBookingRequest;
use App\Http\Resources\ClassBookingResource;
use App\Http\Resources\FitnessClassResource;
use App\Models\FitnessClass;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $classes = FitnessClass::query()
            ->with('trainer.user')
            ->withCount(['bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->where('is_active', true)
            ->when($request->query('date'), fn ($query, string $date) => $query->whereDate('class_date', $date))
            ->orderBy('class_date')
            ->orderBy('start_time')
            ->get();

        return ApiResponder::success(FitnessClassResource::collection($classes), 'Classes retrieved');
    }

    public function book(BookClassRequest $request, BookClassAction $action): JsonResponse
    {
        $booking = $action->execute($request->user()->member, (int) $request->validated('class_id'));

        return ApiResponder::success(new ClassBookingResource($booking->load('fitnessClass.trainer.user')), 'Class booked', 201);
    }

    public function cancel(CancelBookingRequest $request, CancelBookingAction $action): JsonResponse
    {
        $booking = $action->execute($request->user()->member, (int) $request->validated('booking_id'));

        return ApiResponder::success(new ClassBookingResource($booking->load('fitnessClass.trainer.user')), 'Booking cancelled');
    }

    public function myBookings(Request $request): JsonResponse
    {
        $bookings = $request->user()->member->bookings()
            ->with('fitnessClass.trainer.user')
            ->latest('booked_at')
            ->get();

        return ApiResponder::success(ClassBookingResource::collection($bookings), 'Bookings retrieved');
    }
}
