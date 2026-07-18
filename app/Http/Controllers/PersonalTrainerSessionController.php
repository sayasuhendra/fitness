<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PersonalTraining\StorePersonalTrainerSessionRequest;
use App\Http\Resources\PersonalTrainerSessionResource;
use App\Models\PersonalTrainerSession;
use App\Services\ApiResponder;
use App\Services\Notifications\MemberNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PersonalTrainerSessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sessions = $request->user()->member->personalTrainerSessions()
            ->with(['trainer.user', 'paymentConfirmations'])
            ->latest('scheduled_at')
            ->get();

        return ApiResponder::success(PersonalTrainerSessionResource::collection($sessions), 'Personal trainer sessions retrieved');
    }

    public function store(StorePersonalTrainerSessionRequest $request): JsonResponse
    {
        $member = $request->user()->member;
        $accessType = $request->validated('access_type');
        $membership = $member->activeMembership();

        if ($accessType === 'membership' && ($membership === null || ! $membership->includes_personal_trainer)) {
            throw ValidationException::withMessages(['membership' => 'Paket personal trainer aktif diperlukan untuk membuat jadwal PT.']);
        }

        $session = PersonalTrainerSession::query()->create([
            'member_id' => $member->id,
            'trainer_id' => (int) $request->validated('trainer_id'),
            'membership_purchase_id' => $accessType === 'membership' ? $membership?->id : null,
            'scheduled_at' => $request->validated('scheduled_at'),
            'duration_minutes' => $request->validated('duration_minutes') ?? 60,
            'status' => $accessType === 'one_time' ? 'pending_payment' : 'scheduled',
            'access_type' => $accessType,
            'amount' => $accessType === 'one_time' ? 80000 : 0,
            'payment_method' => $request->validated('payment_method'),
            'payment_reference' => $accessType === 'one_time' ? 'MANUAL-PT-'.Str::upper(Str::random(10)) : null,
            'member_note' => $request->validated('member_note'),
        ]);

        app(MemberNotificationService::class)->send(
            $member->user,
            $accessType === 'one_time' ? 'Sesi PT menunggu pembayaran' : 'Jadwal PT berhasil dibuat',
            $accessType === 'one_time'
                ? 'Jadwal personal trainer sudah dibuat. Silakan selesaikan pembayaran agar jadwal dikonfirmasi.'
                : 'Jadwal personal trainer Anda sudah masuk. Sampai jumpa di Akhwat Gym.',
            $accessType === 'one_time' ? 'personal_training_pending_payment' : 'personal_training_scheduled',
            $accessType === 'one_time'
                ? '/payments/manual?payable_type=personal_trainer_session&payable_id='.$session->id.'&amount='.$session->amount.'&payment_method='.($session->payment_method ?? 'qris')
                : '/personal-training',
        );

        return ApiResponder::success(new PersonalTrainerSessionResource($session->load(['trainer.user', 'paymentConfirmations'])), 'Personal trainer session created', 201);
    }
}
