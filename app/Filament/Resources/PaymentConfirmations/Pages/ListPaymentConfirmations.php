<?php

namespace App\Filament\Resources\PaymentConfirmations\Pages;

use App\Filament\Resources\PaymentConfirmations\PaymentConfirmationResource;
use Filament\Resources\Pages\ListRecords;

class ListPaymentConfirmations extends ListRecords
{
    protected static string $resource = PaymentConfirmationResource::class;
}
