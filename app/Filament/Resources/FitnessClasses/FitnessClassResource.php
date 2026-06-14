<?php

namespace App\Filament\Resources\FitnessClasses;

use App\Filament\Resources\FitnessClasses\Pages\CreateFitnessClass;
use App\Filament\Resources\FitnessClasses\Pages\EditFitnessClass;
use App\Filament\Resources\FitnessClasses\Pages\ListFitnessClasses;
use App\Filament\Resources\FitnessClasses\Schemas\FitnessClassForm;
use App\Filament\Resources\FitnessClasses\Tables\FitnessClassesTable;
use App\Models\FitnessClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FitnessClassResource extends Resource
{
    protected static ?string $model = FitnessClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $navigationLabel = 'Class Schedules';

    protected static ?string $modelLabel = 'Class Schedule';

    protected static string|UnitEnum|null $navigationGroup = 'Classes';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return FitnessClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FitnessClassesTable::configure($table);
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
            'index' => ListFitnessClasses::route('/'),
            'create' => CreateFitnessClass::route('/create'),
            'edit' => EditFitnessClass::route('/{record}/edit'),
        ];
    }
}
