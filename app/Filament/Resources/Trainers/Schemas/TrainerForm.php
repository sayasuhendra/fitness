<?php

namespace App\Filament\Resources\Trainers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TrainerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Trainer Profile')
                    ->description('Trainer details shown in schedules and booking information.')
                    ->schema([
                        Select::make('user_id')
                            ->label('User account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('specialization')
                            ->placeholder('Pilates, Yoga, Strength')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp PT')
                            ->tel()
                            ->placeholder('6281234567890')
                            ->maxLength(32)
                            ->helperText('Nomor ini ditampilkan di aplikasi agar member bisa membuat janji langsung.'),
                        Toggle::make('is_active')
                            ->label('Accepting schedules')
                            ->default(true)
                            ->required(),
                        Textarea::make('bio')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
