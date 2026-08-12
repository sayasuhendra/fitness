<?php

namespace App\Filament\Resources\AppSettings\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('key')
                            ->label('Kode Setting')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        TagsInput::make('value')
                            ->label('Hari Reminder')
                            ->placeholder('7')
                            ->helperText('Isi angka hari sebelum membership habis. Contoh: 7, 3, 1.')
                            ->required(),
                    ]),
            ]);
    }
}
