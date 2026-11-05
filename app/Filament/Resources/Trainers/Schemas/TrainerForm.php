<?php

namespace App\Filament\Resources\Trainers\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;

class TrainerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun Instruktur Kelas')
                    ->description('Pilih akun yang sudah ada, atau buat akun baru untuk instruktur kelas.')
                    ->schema([
                        Radio::make('user_mode')
                            ->label('Cara menghubungkan akun')
                            ->options([
                                'existing' => 'Pilih akun yang sudah ada',
                                'new' => 'Buat akun baru',
                            ])
                            ->default('new')
                            ->live()
                            ->required()
                            ->dehydrated(fn (string $operation): bool => $operation === 'create')
                            ->visibleOn('create'),
                        Select::make('user_id')
                            ->label('Akun User')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query, string $operation): Builder => $operation === 'create'
                                    ? $query->whereDoesntHave('trainer')
                                    : $query
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->name} ({$record->email})")
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get, string $operation): bool => $operation === 'edit' || $get('user_mode') === 'existing')
                            ->visible(fn (Get $get, string $operation): bool => $operation === 'edit' || $get('user_mode') === 'existing'),
                        TextInput::make('user_name')
                            ->label('Nama Instruktur')
                            ->afterStateHydrated(fn ($component, $record) => $component->state($record?->user?->name))
                            ->required()
                            ->maxLength(255)
                            ->visibleOn('edit'),
                        TextInput::make('user_phone')
                            ->label('Nomor WhatsApp / HP')
                            ->tel()
                            ->afterStateHydrated(fn ($component, $record) => $component->state($record?->user?->phone))
                            ->maxLength(32)
                            ->visibleOn('edit'),
                        TextInput::make('new_user_name')
                            ->label('Nama Akun Baru')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('user_mode') === 'new')
                            ->visible(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new')
                            ->dehydrated(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new'),
                        TextInput::make('new_user_email')
                            ->label('Email Akun Baru')
                            ->email()
                            ->maxLength(255)
                            ->unique(table: 'users', column: 'email')
                            ->required(fn (Get $get): bool => $get('user_mode') === 'new')
                            ->visible(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new')
                            ->dehydrated(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new'),
                        TextInput::make('new_user_phone')
                            ->label('Nomor HP Akun Baru')
                            ->tel()
                            ->maxLength(32)
                            ->visible(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new')
                            ->dehydrated(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new'),
                        TextInput::make('new_user_password')
                            ->label('Password Akun Baru')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->required(fn (Get $get): bool => $get('user_mode') === 'new')
                            ->visible(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new')
                            ->dehydrated(fn (Get $get, string $operation): bool => $operation === 'create' && $get('user_mode') === 'new'),
                    ])
                    ->columns(2),
                Section::make('Profil Instruktur Kelas')
                    ->description('Data ini dipakai untuk jadwal kelas bersama seperti Zumba, Yoga, Aerobic, dan kelas sejenis.')
                    ->schema([
                        TextInput::make('specialization')
                            ->label('Keahlian')
                            ->placeholder('Pilates, Yoga, Strength')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp Instruktur')
                            ->tel()
                            ->placeholder('6281234567890')
                            ->maxLength(32),
                        Toggle::make('is_active')
                            ->label('Aktif menerima jadwal')
                            ->default(true)
                            ->required(),
                        Textarea::make('bio')
                            ->label('Bio')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
