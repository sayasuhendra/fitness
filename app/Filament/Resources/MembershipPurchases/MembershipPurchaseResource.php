<?php

namespace App\Filament\Resources\MembershipPurchases;

use App\Filament\Resources\MembershipPurchases\Pages\CreateMembershipPurchase;
use App\Filament\Resources\MembershipPurchases\Pages\EditMembershipPurchase;
use App\Filament\Resources\MembershipPurchases\Pages\ListMembershipPurchases;
use App\Filament\Resources\MembershipPurchases\Schemas\MembershipPurchaseForm;
use App\Filament\Resources\MembershipPurchases\Tables\MembershipPurchasesTable;
use App\Models\MembershipPurchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MembershipPurchaseResource extends Resource
{
    protected static ?string $model = MembershipPurchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $navigationLabel = 'Transaksi Membership';

    protected static ?string $modelLabel = 'Transaksi Membership';

    protected static string|UnitEnum|null $navigationGroup = 'Membership & Paket';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return MembershipPurchaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MembershipPurchasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembershipPurchases::route('/'),
            'create' => CreateMembershipPurchase::route('/create'),
            'edit' => EditMembershipPurchase::route('/{record}/edit'),
        ];
    }
}
