<?php

namespace App\Filament\Resources\FitnessClasses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FitnessClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Class Details')
                    ->description('Core information members see before booking.')
                    ->schema([
                        TextInput::make('name')
                            ->placeholder('Morning Pilates')
                            ->required()
                            ->maxLength(120),
                        Select::make('trainer_id')
                            ->label('Trainer')
                            ->relationship('trainer', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->user?->name ?? "Trainer #{$record->id}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('capacity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        TextInput::make('location')
                            ->placeholder('Studio A')
                            ->required()
                            ->maxLength(120),
                        Toggle::make('is_active')
                            ->label('Open for booking')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Schedule')
                    ->schema([
                        DatePicker::make('class_date')
                            ->label('Date')
                            ->native(false)
                            ->required(),
                        TimePicker::make('start_time')
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('end_time')
                            ->seconds(false)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
