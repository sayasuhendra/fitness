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
                Section::make('Member & Paket')
                    ->schema([
                        Select::make('member_id')
                            ->label('Member')
                            ->relationship('member', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->user?->name ?? "Member #{$record->id}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('membership_package_id')
                            ->label('Paket')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                                if ($state) {
                                    $package = \App\Models\MembershipPackage::find($state);
                                    if ($package) {
                                        $set('amount', $package->price);
                                        $set('includes_personal_trainer', $package->includes_personal_trainer);
                                        $set('visits_allowed', $package->has_visit_limit ? $package->visit_limit : null);
                                        $startsAt = $get('starts_at') ? \Illuminate\Support\Carbon::parse($get('starts_at')) : now();
                                        $set('starts_at', $startsAt->toDateTimeString());
                                        $set('expires_at', $startsAt->copy()->addDays($package->duration_days)->toDateTimeString());
                                    }
                                }
                            })
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending_payment' => 'Menunggu Pembayaran',
                                'active' => 'Aktif',
                                'expired' => 'Berakhir',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending_payment')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                                if ($state === 'active') {
                                    if (! $get('starts_at')) {
                                        $set('starts_at', now()->toDateTimeString());
                                    }
                                    $packageId = $get('membership_package_id');
                                    if ($packageId && ! $get('expires_at')) {
                                        $package = \App\Models\MembershipPackage::find($packageId);
                                        if ($package) {
                                            $startsAt = \Illuminate\Support\Carbon::parse($get('starts_at') ?? now());
                                            $set('expires_at', $startsAt->copy()->addDays($package->duration_days)->toDateTimeString());
                                        }
                                    }
                                }
                            })
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('Pembayaran & Masa Aktif')
                    ->schema([
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Transfer Bank',
                                'cash' => 'Tunai',
                                'manual_transfer' => 'Transfer Manual',
                            ])
                            ->required(),
                        TextInput::make('amount')
                            ->label('Nominal')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->dehydrated()
                            ->required(),
                        TextInput::make('payment_reference')
                            ->label('Kode Pembayaran')
                            ->maxLength(120),
                        DateTimePicker::make('starts_at')
                            ->label('Mulai Aktif')
                            ->native(false),
                        DateTimePicker::make('expires_at')
                            ->label('Berakhir')
                            ->native(false),
                    ])
                    ->columns(3),
                Section::make('Benefit Kunjungan')
                    ->schema([
                        Toggle::make('includes_personal_trainer')
                            ->label('Termasuk Personal Trainer')
                            ->dehydrated(),
                        TextInput::make('visits_allowed')
                            ->label('Maksimal Kunjungan')
                            ->numeric()
                            ->dehydrated()
                            ->helperText('Kosongkan untuk kunjungan tanpa batas (unlimited).'),
                        TextInput::make('visits_used')
                            ->label('Kunjungan Terpakai')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
