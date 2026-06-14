<?php

namespace App\Filament\Resources\MembershipPackages;

use App\Filament\Resources\MembershipPackages\Pages\CreateMembershipPackage;
use App\Filament\Resources\MembershipPackages\Pages\EditMembershipPackage;
use App\Filament\Resources\MembershipPackages\Pages\ListMembershipPackages;
use App\Filament\Resources\MembershipPackages\Schemas\MembershipPackageForm;
use App\Filament\Resources\MembershipPackages\Tables\MembershipPackagesTable;
use App\Models\MembershipPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MembershipPackageResource extends Resource
{
    protected static ?string $model = MembershipPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Identification;

    protected static ?string $navigationLabel = 'Membership Plans';

    protected static ?string $modelLabel = 'Membership Plan';

    protected static string|UnitEnum|null $navigationGroup = 'Commerce';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return MembershipPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MembershipPackagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembershipPackages::route('/'),
            'create' => CreateMembershipPackage::route('/create'),
            'edit' => EditMembershipPackage::route('/{record}/edit'),
        ];
    }
}
