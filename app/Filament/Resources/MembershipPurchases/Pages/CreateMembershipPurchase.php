<?php

namespace App\Filament\Resources\MembershipPurchases\Pages;

use App\Filament\Resources\MembershipPurchases\MembershipPurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMembershipPurchase extends CreateRecord
{
    protected static string $resource = MembershipPurchaseResource::class;
}
