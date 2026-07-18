<?php

namespace App\Filament\Resources\QrisPaymentMethods\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QrisPaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('QRIS Pembayaran')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama QRIS')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        FileUpload::make('image_path')
                            ->label('Gambar QRIS')
                            ->disk('public')
                            ->directory('payment/qris')
                            ->image()
                            ->downloadable()
                            ->openable()
                            ->required()
                            ->columnSpanFull(),
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
