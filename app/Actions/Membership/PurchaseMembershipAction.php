<?php

declare(strict_types=1);

namespace App\Actions\Membership;

use App\DTO\PurchaseMembershipData;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\User;
use App\Services\Notifications\MemberNotificationService;
use App\Support\AdminShift;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class PurchaseMembershipAction
{
    public function execute(Member $member, PurchaseMembershipData $data, ?User $admin = null): MembershipPurchase
    {
        $purchase = DB::transaction(function () use ($member, $data, $admin): MembershipPurchase {
            $package = MembershipPackage::query()->where('is_active', true)->findOrFail($data->packageId);

            return MembershipPurchase::query()->create([
                'member_id' => $member->id,
                ...AdminShift::stamp($admin),
                'membership_package_id' => $package->id,
                'starts_at' => null,
                'expires_at' => null,
                'status' => 'pending_payment',
                'includes_personal_trainer' => $package->includes_personal_trainer,
                'visits_allowed' => $package->has_visit_limit ? $package->visit_limit : null,
                'visits_used' => 0,
                'payment_method' => $data->paymentMethod,
                'amount' => $package->price,
                'payment_reference' => 'MANUAL-'.Str::upper(Str::random(12)),
            ]);
        });

        app(MemberNotificationService::class)->send(
            $member->user,
            'Paket menunggu pembayaran',
            'Pembelian paket '.$purchase->package->name.' sudah dibuat. Silakan konfirmasi pembayaran agar paket aktif.',
            'membership_pending_payment',
            '/payments/manual?payable_type=membership_purchase&payable_id='.$purchase->id.'&amount='.$purchase->amount.'&payment_method='.$purchase->payment_method,
        );

        return $purchase;
    }
}
