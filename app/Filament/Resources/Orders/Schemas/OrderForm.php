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
                Section::make('Order Summary')
                    ->description('Store order status and payment audit data.')
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
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Bank Transfer',
                                'midtrans' => 'Midtrans',
                                'cash' => 'Cash',
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
