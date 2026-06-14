<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
}
