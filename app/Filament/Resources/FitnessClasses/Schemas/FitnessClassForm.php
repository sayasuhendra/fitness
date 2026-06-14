<?php

namespace App\Filament\Resources\FitnessClasses\Schemas;

use Filament\Forms\Components\CheckboxList;
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
                        Select::make('class_type')
                            ->label('Class Type')
                            ->options([
                                'zumba' => 'Zumba',
                                'yoga' => 'Yoga',
                                'circuit_training' => 'Circuit Training',
                                'pilates' => 'Pilates',
                                'strength' => 'Strength',
                                'general' => 'General',
                            ])
                            ->default('general')
                            ->required(),
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
                            ->label('Start Date')
                            ->native(false)
                            ->required(),
                        Toggle::make('is_recurring')
                            ->label('Repeat weekly')
                            ->live()
                            ->default(false),
                        DatePicker::make('recurrence_ends_at')
                            ->label('Repeat until')
                            ->native(false)
                            ->visible(fn ($get): bool => (bool) $get('is_recurring')),
                        TimePicker::make('start_time')
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('end_time')
                            ->seconds(false)
                            ->required(),
                        CheckboxList::make('recurring_days')
                            ->label('Repeat on')
                            ->options([
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                            ])
                            ->columns(4)
                            ->visible(fn ($get): bool => (bool) $get('is_recurring'))
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Visitor Options')
                    ->description('Control one-time visitor access and optional trainer add-on.')
                    ->schema([
                        Toggle::make('allow_drop_in')
                            ->label('Allow one-time visitor')
                            ->default(true),
                        TextInput::make('drop_in_price')
                            ->label('One-time visitor price')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        TextInput::make('trainer_addon_price')
                            ->label('Personal trainer add-on')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ])
                    ->columns(3),
            ]);
    }
}
