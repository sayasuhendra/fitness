<?php

namespace App\Filament\Resources\QrisPaymentMethods\Pages;

use App\Filament\Resources\QrisPaymentMethods\QrisPaymentMethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQrisPaymentMethods extends ListRecords
{
    protected static string $resource = QrisPaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
