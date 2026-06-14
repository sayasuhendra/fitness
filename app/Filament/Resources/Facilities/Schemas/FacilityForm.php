<?php

namespace App\Filament\Resources\Facilities\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FacilityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Facility')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(140)
                            ->unique(ignoreRecord: true),
                        TextInput::make('icon')
                            ->maxLength(80)
                            ->placeholder('wifi'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Visible')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
