<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Models\ClassBooking;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\PaymentConfirmation;
use App\Models\PersonalTrainerSession;
use App\Models\Product;
use App\Models\User;
use App\Services\Notifications\MemberNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApprovePaymentConfirmationAction
{
    public function execute(PaymentConfirmation $confirmation, User $admin, ?string $adminNote = null): PaymentConfirmation
    {
        $confirmation = DB::transaction(function () use ($confirmation, $admin, $adminNote): PaymentConfirmation {
            $confirmation = PaymentConfirmation::query()
                ->with('payable')
                ->lockForUpdate()
                ->findOrFail($confirmation->id);

            if ($confirmation->status === 'approved') {
                return $confirmation;
            }

            if ($confirmation->status === 'rejected') {
                throw ValidationException::withMessages([
                    'status' => 'Pembayaran yang sudah ditolak tidak bisa langsung diterima. Minta member upload bukti ulang.',
                ]);
            }

            $payable = $confirmation->payable;

            if ($payable instanceof MembershipPurchase) {
                $startsAt = now();
                $payable->update([
                    'status' => 'active',
                    'starts_at' => $payable->starts_at ?? $startsAt,
                    'expires_at' => $payable->expires_at ?? $startsAt->copy()->addDays($payable->package->duration_days),
                ]);
            }

            if ($payable instanceof Order) {
                $payable->loadMissing('items.product');

                foreach ($payable->items as $item) {
                    /** @var Product $product */
                    $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);

                    if ($product->stock < $item->quantity) {
                        throw ValidationException::withMessages([
                            'stock' => "{$product->name} tidak memiliki stok yang cukup.",
                        ]);
                    }

                    $product->decrement('stock', $item->quantity);
                }

                $payable->update(['status' => 'paid']);
            }

            if ($payable instanceof ClassBooking) {
                $payable->update(['status' => 'confirmed']);
            }

            if ($payable instanceof PersonalTrainerSession) {
                $payable->update(['status' => 'scheduled']);
            }

            $confirmation->update([
                'status' => 'approved',
                'admin_note' => $adminNote,
                'verified_by' => $admin->id,
                'verified_at' => now(),
            ]);

            return $confirmation->refresh();
        });

        $this->notifyMember($confirmation);

        return $confirmation;
    }

    private function notifyMember(PaymentConfirmation $confirmation): void
    {
        $confirmation->loadMissing('member.user', 'payable');
        $user = $confirmation->member?->user;

        if ($user === null || $confirmation->status !== 'approved') {
            return;
        }

        [$title, $body, $actionUrl] = match (true) {
            $confirmation->payable instanceof MembershipPurchase => [
                'Paket membership aktif',
                'Pembayaran paket Anda sudah diterima. Sekarang Anda bisa menggunakan benefit Akhwat Gym.',
                '/memberships/history',
            ],
            $confirmation->payable instanceof Order => [
                'Pesanan sudah dibayar',
                'Pembayaran pesanan Anda sudah diterima. Admin akan menyiapkan pesanan Anda.',
                '/orders/'.$confirmation->payable->getKey(),
            ],
            $confirmation->payable instanceof ClassBooking => [
                'Booking kelas dikonfirmasi',
                'Pembayaran kunjungan kelas Anda sudah diterima. Sampai jumpa di kelas.',
                '/classes/my-bookings',
            ],
            $confirmation->payable instanceof PersonalTrainerSession => [
                'Jadwal PT dikonfirmasi',
                'Pembayaran sesi personal trainer sudah diterima. Jadwal Anda sudah masuk.',
                '/personal-training',
            ],
            default => [
                'Pembayaran diterima',
                'Pembayaran Anda sudah diterima oleh admin Akhwat Gym.',
                null,
            ],
        };

        app(MemberNotificationService::class)->send($user, $title, $body, 'payment_approved', $actionUrl);
    }
}
