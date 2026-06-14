<?php

namespace App\Filament\Resources\MembershipPurchases\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MembershipPurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Member & Package')
                    ->schema([
                        Select::make('member_id')
                            ->label('Member')
                            ->relationship('member', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->user?->name ?? "Member #{$record->id}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('membership_package_id')
                            ->label('Package')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'expired' => 'Expired',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('Payment & Validity')
                    ->schema([
                        Select::make('payment_method')
                            ->options([
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Bank Transfer',
                                'midtrans' => 'Midtrans',
                                'cash' => 'Cash',
                            ])
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('payment_reference')
                            ->maxLength(120),
                        DateTimePicker::make('starts_at')
                            ->native(false),
                        DateTimePicker::make('expires_at')
                            ->native(false),
                    ])
                    ->columns(3),
                Section::make('Visit Benefits')
                    ->schema([
                        Toggle::make('includes_personal_trainer')
                            ->label('Includes Personal Trainer'),
                        TextInput::make('visits_allowed')
                            ->label('Maximum Visits')
                            ->numeric()
                            ->helperText('Leave empty for unlimited visits.'),
                        TextInput::make('visits_used')
                            ->label('Visits Used')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
