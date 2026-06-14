<?php

namespace App\Filament\Resources\MembershipPurchases\Pages;

use App\Filament\Resources\MembershipPurchases\MembershipPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMembershipPurchase extends EditRecord
{
    protected static string $resource = MembershipPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
