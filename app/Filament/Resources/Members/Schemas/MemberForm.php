<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Member Identity')
                    ->description('Connect this member profile to an app user and keep their member code consistent.')
                    ->schema([
                        Select::make('user_id')
                            ->label('User account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('member_code')
                            ->label('Member code')
                            ->placeholder('MBR000001')
                            ->required()
                            ->maxLength(32),
                        DatePicker::make('joined_at')
                            ->label('Joined date')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
