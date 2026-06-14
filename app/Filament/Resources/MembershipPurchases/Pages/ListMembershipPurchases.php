<?php

namespace App\Filament\Resources\MembershipPurchases\Pages;

use App\Filament\Resources\MembershipPurchases\MembershipPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMembershipPurchases extends ListRecords
{
    protected static string $resource = MembershipPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
