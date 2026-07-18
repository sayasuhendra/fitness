<?php

namespace App\Filament\Resources\PersonalTrainerSessions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonalTrainerSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jadwal Sesi')
                    ->schema([
                        Select::make('member_id')
                            ->label('Member')
                            ->relationship('member', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->user?->name ?? "Member #{$record->id}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('trainer_id')
                            ->label('Trainer')
                            ->relationship('trainer', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->user?->name ?? "Trainer #{$record->id}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('membership_purchase_id')
                            ->label('Membership PT')
                            ->relationship('membershipPurchase', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->package?->name.' #'.$record->id)
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('scheduled_at')
                            ->label('Waktu Sesi')
                            ->native(false)
                            ->required(),
                        TextInput::make('duration_minutes')
                            ->label('Durasi Menit')
                            ->numeric()
                            ->default(60)
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending_payment' => 'Menunggu Pembayaran',
                                'scheduled' => 'Terjadwal',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('scheduled')
                            ->required(),
                        Select::make('access_type')
                            ->label('Akses')
                            ->options([
                                'membership' => 'Paket PT',
                                'one_time' => 'Sekali Datang',
                            ])
                            ->default('membership')
                            ->required(),
                        TextInput::make('amount')
                            ->label('Nominal')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required(),
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Transfer Bank',
                                'cash' => 'Tunai',
                            ]),
                        TextInput::make('payment_reference')
                            ->label('Referensi Pembayaran')
                            ->maxLength(120),
                        DateTimePicker::make('completed_at')
                            ->label('Selesai Pada')
                            ->native(false),
                        Textarea::make('member_note')
                            ->label('Catatan Member')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
