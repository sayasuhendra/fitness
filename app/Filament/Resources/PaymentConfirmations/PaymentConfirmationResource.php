<?php

namespace App\Filament\Resources\PaymentConfirmations;

use App\Filament\Resources\PaymentConfirmations\Pages\EditPaymentConfirmation;
use App\Filament\Resources\PaymentConfirmations\Pages\ListPaymentConfirmations;
use App\Filament\Resources\PaymentConfirmations\Schemas\PaymentConfirmationForm;
use App\Filament\Resources\PaymentConfirmations\Tables\PaymentConfirmationsTable;
use App\Models\PaymentConfirmation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PaymentConfirmationResource extends Resource
{
    protected static ?string $model = PaymentConfirmation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $navigationLabel = 'Konfirmasi Pembayaran';

    protected static ?string $modelLabel = 'Konfirmasi Pembayaran';

    protected static string|UnitEnum|null $navigationGroup = 'Pembayaran';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return PaymentConfirmationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentConfirmationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentConfirmations::route('/'),
            'edit' => EditPaymentConfirmation::route('/{record}/edit'),
        ];
    }
}
