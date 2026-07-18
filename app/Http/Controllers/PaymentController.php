<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Payments\StorePaymentConfirmationRequest;
use App\Http\Resources\PaymentConfirmationResource;
use App\Http\Resources\PaymentMethodResource;
use App\Models\BankAccount;
use App\Models\ClassBooking;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\PaymentConfirmation;
use App\Models\PersonalTrainerSession;
use App\Models\QrisPaymentMethod;
use App\Services\ApiResponder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function methods(): JsonResponse
    {
        $bankAccounts = BankAccount::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('bank_name')
            ->get();

        $qrisMethods = QrisPaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return ApiResponder::success([
            'bank_accounts' => PaymentMethodResource::collection($bankAccounts),
            'qris' => PaymentMethodResource::collection($qrisMethods),
            'whatsapp_number' => config('services.akhwat_gym.whatsapp_number'),
        ], 'Payment methods retrieved');
    }

    public function confirm(StorePaymentConfirmationRequest $request): JsonResponse
    {
        $member = $request->user()->member;
        $payable = $this->resolvePayable(
            type: $request->validated('payable_type'),
            id: (int) $request->validated('payable_id'),
            memberId: $member->id,
        );

        $proofPath = $request->hasFile('proof')
            ? $request->file('proof')->store('payment-proofs', 'public')
            : null;

        $confirmation = DB::transaction(function () use ($request, $member, $payable, $proofPath): PaymentConfirmation {
            return PaymentConfirmation::query()->create([
                'payable_type' => $payable->getMorphClass(),
                'payable_id' => $payable->getKey(),
                'member_id' => $member->id,
                'payment_method' => $request->validated('payment_method'),
                'amount' => $this->payableAmount($payable),
                'status' => 'pending',
                'proof_path' => $proofPath,
                'whatsapp_url' => $this->whatsappUrl($payable),
                'member_note' => $request->validated('member_note'),
            ]);
        });

        return ApiResponder::success(new PaymentConfirmationResource($confirmation), 'Payment confirmation submitted', 201);
    }

    private function resolvePayable(string $type, int $id, int $memberId): Model
    {
        return match ($type) {
            'membership_purchase' => MembershipPurchase::query()
                ->where('member_id', $memberId)
                ->findOrFail($id),
            'order' => Order::query()
                ->where('member_id', $memberId)
                ->findOrFail($id),
            'class_booking' => ClassBooking::query()
                ->where('member_id', $memberId)
                ->findOrFail($id),
            'personal_trainer_session' => PersonalTrainerSession::query()
                ->where('member_id', $memberId)
                ->findOrFail($id),
        };
    }

    private function payableAmount(Model $payable): float
    {
        return match (true) {
            $payable instanceof MembershipPurchase => (float) $payable->amount,
            $payable instanceof Order => (float) $payable->total_price,
            $payable instanceof ClassBooking => (float) $payable->amount,
            $payable instanceof PersonalTrainerSession => (float) $payable->amount,
            default => 0,
        };
    }

    private function whatsappUrl(Model $payable): string
    {
        $number = preg_replace('/\D+/', '', (string) config('services.akhwat_gym.whatsapp_number'));
        $message = rawurlencode("Assalamu'alaikum Admin Akhwat Gym, saya sudah melakukan pembayaran untuk {$payable->getMorphClass()} #{$payable->getKey()}. Mohon dicek ya.");

        return "https://wa.me/{$number}?text={$message}";
    }
}
