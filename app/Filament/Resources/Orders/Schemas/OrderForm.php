<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan Pesanan')
                    ->description('Status pesanan dan pembayaran manual.')
                    ->schema([
                        Select::make('member_id')
                            ->label('Member')
                            ->relationship('member', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->user?->name ?? "Member #{$record->id}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending_payment' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending_payment')
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Transfer Bank',
                                'cash' => 'Tunai',
                            ])
                            ->required(),
                        TextInput::make('total_price')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('payment_reference')
                            ->maxLength(120),
                    ])
                    ->columns(3),
            ]);
    }
}
