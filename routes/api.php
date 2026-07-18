<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PersonalTrainerSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::put('auth/profile', [AuthController::class, 'updateProfile']);
        Route::put('profile', [ProfileController::class, 'update']);
        Route::put('profile/change-password', [ProfileController::class, 'changePassword']);

        Route::get('memberships', [MembershipController::class, 'index']);
        Route::post('memberships/purchase', [MembershipController::class, 'purchase']);
        Route::get('memberships/history', [MembershipController::class, 'history']);

        Route::get('classes', [ClassController::class, 'index']);
        Route::get('trainers', [TrainerController::class, 'index']);
        Route::post('classes/book', [ClassController::class, 'book']);
        Route::post('classes/cancel', [ClassController::class, 'cancel']);
        Route::get('classes/my-bookings', [ClassController::class, 'myBookings']);
        Route::get('personal-trainer-sessions', [PersonalTrainerSessionController::class, 'index']);
        Route::post('personal-trainer-sessions', [PersonalTrainerSessionController::class, 'store']);

        Route::get('facilities', [FacilityController::class, 'index']);

        Route::get('payments/methods', [PaymentController::class, 'methods']);
        Route::post('payments/confirmations', [PaymentController::class, 'confirm']);

        Route::get('attendance/history', [AttendanceController::class, 'history']);
        Route::get('attendance/qr-code', [AttendanceController::class, 'qrCode']);
        Route::post('attendance/check-in', [AttendanceController::class, 'checkIn']);

        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{product}', [ProductController::class, 'show']);

        Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);
        Route::post('orders/{order}/reorder', [OrderController::class, 'reorder']);

        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/fcm-token', [NotificationController::class, 'storeFcmToken']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    });
});
