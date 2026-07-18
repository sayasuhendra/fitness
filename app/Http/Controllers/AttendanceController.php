<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Attendance\CheckInMemberAction;
use App\Http\Requests\Attendance\CheckInRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\ClassBooking;
use App\Models\Member;
use App\Models\PersonalTrainerSession;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function history(Request $request): JsonResponse
    {
        $attendances = $request->user()->member->attendances()
            ->with('fitnessClass')
            ->latest('check_in_time')
            ->get();

        return ApiResponder::success(AttendanceResource::collection($attendances), 'Attendance history retrieved');
    }

    public function qrCode(Request $request): JsonResponse
    {
        $payload = Crypt::encryptString(json_encode([
            'member_id' => $request->user()->member->id,
            'expires_at' => now()->addMinutes(10)->toISOString(),
        ], JSON_THROW_ON_ERROR));

        return ApiResponder::success($payload, 'QR check-in code generated');
    }

    public function checkIn(CheckInRequest $request, CheckInMemberAction $action): JsonResponse
    {
        $memberId = $request->validated('member_id');

        if ($request->filled('qr_payload')) {
            $payload = json_decode(Crypt::decryptString($request->validated('qr_payload')), true, 512, JSON_THROW_ON_ERROR);

            if (Carbon::parse($payload['expires_at'])->isPast()) {
                throw ValidationException::withMessages(['qr_payload' => 'QR member sudah kedaluwarsa.']);
            }

            $memberId = $payload['member_id'];
        }

        if ($memberId === null) {
            throw ValidationException::withMessages(['member_id' => 'Pilih member atau scan QR member.']);
        }

        $member = Member::query()->findOrFail($memberId);
        $booking = $request->filled('class_booking_id')
            ? ClassBooking::query()->with('fitnessClass')->findOrFail($request->validated('class_booking_id'))
            : null;
        $personalTrainerSession = $request->filled('personal_trainer_session_id')
            ? PersonalTrainerSession::query()->findOrFail($request->validated('personal_trainer_session_id'))
            : null;

        $attendance = $action->execute(
            member: $member,
            attendanceType: $request->validated('attendance_type'),
            booking: $booking,
            personalTrainerSession: $personalTrainerSession,
            location: $request->validated('location') ?? null,
        );

        return ApiResponder::success(new AttendanceResource($attendance->load('fitnessClass')), 'Check-in berhasil dicatat', 201);
    }
}
