<?php

namespace App\Filament\Resources\PaymentConfirmations\Pages;

use App\Filament\Resources\PaymentConfirmations\PaymentConfirmationResource;
use Filament\Resources\Pages\EditRecord;

class EditPaymentConfirmation extends EditRecord
{
    protected static string $resource = PaymentConfirmationResource::class;
}
