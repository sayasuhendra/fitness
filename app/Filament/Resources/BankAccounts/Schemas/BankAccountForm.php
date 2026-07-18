<?php

namespace App\Filament\Resources\BankAccounts\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BankAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Rekening')
                    ->schema([
                        TextInput::make('bank_name')
                            ->label('Nama Bank')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('account_name')
                            ->label('Nama Pemilik Rekening')
                            ->required()
                            ->maxLength(160),
                        TextInput::make('account_number')
                            ->label('Nomor Rekening')
                            ->required()
                            ->maxLength(80),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Textarea::make('instructions')
                            ->label('Instruksi Pembayaran')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Tampilkan di Aplikasi')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
