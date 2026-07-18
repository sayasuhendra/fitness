<?php

namespace App\Filament\Resources\QrisPaymentMethods;

use App\Filament\Resources\QrisPaymentMethods\Pages\CreateQrisPaymentMethod;
use App\Filament\Resources\QrisPaymentMethods\Pages\EditQrisPaymentMethod;
use App\Filament\Resources\QrisPaymentMethods\Pages\ListQrisPaymentMethods;
use App\Filament\Resources\QrisPaymentMethods\Schemas\QrisPaymentMethodForm;
use App\Filament\Resources\QrisPaymentMethods\Tables\QrisPaymentMethodsTable;
use App\Models\QrisPaymentMethod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QrisPaymentMethodResource extends Resource
{
    protected static ?string $model = QrisPaymentMethod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QrCode;

    protected static ?string $navigationLabel = 'QRIS';

    protected static ?string $modelLabel = 'QRIS';

    protected static string|UnitEnum|null $navigationGroup = 'Pembayaran';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return QrisPaymentMethodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QrisPaymentMethodsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQrisPaymentMethods::route('/'),
            'create' => CreateQrisPaymentMethod::route('/create'),
            'edit' => EditQrisPaymentMethod::route('/{record}/edit'),
        ];
    }
}
