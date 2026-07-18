<?php

namespace App\Filament\Resources\QrisPaymentMethods\Pages;

use App\Filament\Resources\QrisPaymentMethods\QrisPaymentMethodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQrisPaymentMethod extends EditRecord
{
    protected static string $resource = QrisPaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
