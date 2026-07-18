<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Check-In')
                    ->schema([
                        Select::make('member_id')
                            ->label('Member')
                            ->relationship('member', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->user?->name ?? "Member #{$record->id}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('attendance_type')
                            ->label('Jenis Check-In')
                            ->options([
                                'gym_visit' => 'Gym Visit',
                                'class_attendance' => 'Kelas',
                                'personal_trainer_session' => 'Personal Trainer',
                            ])
                            ->default('gym_visit')
                            ->required(),
                        Select::make('fitness_class_id')
                            ->label('Kelas')
                            ->relationship('fitnessClass', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('class_booking_id')
                            ->label('Booking Kelas')
                            ->relationship('classBooking', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->fitnessClass?->name} #{$record->id}")
                            ->searchable()
                            ->preload(),
                        Select::make('personal_trainer_session_id')
                            ->label('Sesi PT')
                            ->relationship('personalTrainerSession', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->trainer?->user?->name} - ".$record->scheduled_at?->format('d M Y H:i'))
                            ->searchable()
                            ->preload(),
                        Select::make('membership_purchase_id')
                            ->label('Membership')
                            ->relationship('membershipPurchase', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->package?->name} #{$record->id}")
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('check_in_time')
                            ->label('Waktu Check-In')
                            ->native(false)
                            ->default(now())
                            ->required(),
                        Select::make('status')
                            ->options([
                                'present' => 'Hadir',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('present')
                            ->required(),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->default('Akhwat Gym Studio')
                            ->maxLength(160)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
