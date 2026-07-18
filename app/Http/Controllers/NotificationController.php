<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\StoreFcmTokenRequest;
use App\Http\Resources\NotificationLogResource;
use App\Models\DeviceToken;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return ApiResponder::success(
            NotificationLogResource::collection($request->user()->notifications()->latest()->get()),
            'Notifications retrieved'
        );
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return ApiResponder::success(null, 'Notification marked as read');
    }

    public function storeFcmToken(StoreFcmTokenRequest $request): JsonResponse
    {
        $token = DeviceToken::query()->updateOrCreate(
            ['token' => $request->validated('fcm_token')],
            [
                'user_id' => $request->user()->id,
                'platform' => $request->validated('platform'),
            ],
        );

        return ApiResponder::success([
            'id' => $token->id,
            'platform' => $token->platform,
        ], 'FCM token registered');
    }
}
