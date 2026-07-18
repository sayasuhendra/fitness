<?php

namespace App\Filament\Resources\PersonalTrainerSessions;

use App\Filament\Resources\PersonalTrainerSessions\Pages\CreatePersonalTrainerSession;
use App\Filament\Resources\PersonalTrainerSessions\Pages\EditPersonalTrainerSession;
use App\Filament\Resources\PersonalTrainerSessions\Pages\ListPersonalTrainerSessions;
use App\Filament\Resources\PersonalTrainerSessions\Schemas\PersonalTrainerSessionForm;
use App\Filament\Resources\PersonalTrainerSessions\Tables\PersonalTrainerSessionsTable;
use App\Models\PersonalTrainerSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PersonalTrainerSessionResource extends Resource
{
    protected static ?string $model = PersonalTrainerSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $navigationLabel = 'Sesi Personal Trainer';

    protected static ?string $modelLabel = 'Sesi Personal Trainer';

    protected static string|UnitEnum|null $navigationGroup = 'Classes';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return PersonalTrainerSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonalTrainerSessionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPersonalTrainerSessions::route('/'),
            'create' => CreatePersonalTrainerSession::route('/create'),
            'edit' => EditPersonalTrainerSession::route('/{record}/edit'),
        ];
    }
}
