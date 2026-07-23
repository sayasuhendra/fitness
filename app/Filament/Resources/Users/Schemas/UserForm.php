<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Support\AdminShift;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use SensitiveParameter;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account')
                    ->description('Manage admin account details and panel access roles.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(32),
                        Select::make('admin_shift')
                            ->label('Shift Admin Lokasi')
                            ->options(AdminShift::options())
                            ->placeholder('Tidak ditetapkan')
                            ->helperText('Isi untuk akun Admin di lokasi shift 1 atau shift 2.'),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->helperText('Role admin utama: Owner, Super admin, atau Admin di lokasi.'),
                    ])
                    ->columns(2),
                Section::make('Password')
                    ->description('Required for new users. Leave blank when editing if the password should not change.')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (#[SensitiveParameter] $state): bool => filled($state))
                            ->dehydrateStateUsing(fn (#[SensitiveParameter] $state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('password_confirmation'),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required(fn (Get $get): bool => filled($get('password')))
                            ->visible(fn (Get $get): bool => filled($get('password')))
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }
}
