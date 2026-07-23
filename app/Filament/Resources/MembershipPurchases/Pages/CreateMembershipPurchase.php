<?php

namespace App\Filament\Resources\MembershipPurchases\Pages;

use App\Filament\Resources\MembershipPurchases\MembershipPurchaseResource;
use App\Models\MembershipPackage;
use App\Support\AdminShift;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateMembershipPurchase extends CreateRecord
{
    protected static string $resource = MembershipPurchaseResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $package = MembershipPackage::query()->findOrFail((int) $data['membership_package_id']);
        $startsAt = now();

        return [
            ...$data,
            ...AdminShift::stamp(auth()->user()),
            'amount' => $package->price,
            'includes_personal_trainer' => $package->includes_personal_trainer,
            'visits_allowed' => $package->has_visit_limit ? $package->visit_limit : null,
            'visits_used' => $data['visits_used'] ?? 0,
            'payment_reference' => $data['payment_reference'] ?: 'ADMIN-'.Str::upper(Str::random(10)),
            'starts_at' => $data['status'] === 'active' ? ($data['starts_at'] ?? $startsAt) : ($data['starts_at'] ?? null),
            'expires_at' => $data['status'] === 'active' ? ($data['expires_at'] ?? $startsAt->copy()->addDays($package->duration_days)) : ($data['expires_at'] ?? null),
        ];
    }
}
