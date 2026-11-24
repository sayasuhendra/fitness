<?php

namespace App\Filament\Resources\MembershipPackages\Schemas;

use App\Support\ClassType;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MembershipPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Paket')
                    ->description('Tentukan nama paket, tipe membership, dan masa berlaku.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Paket')
                            ->placeholder('Contoh: Paket Bulanan Regular')
                            ->required()
                            ->maxLength(120),
                        Select::make('package_type')
                            ->label('Tipe Paket')
                            ->options([
                                'membership' => 'Membership (Langganan)',
                                'one_time' => 'Kunjungan Sekali (One-time)',
                            ])
                            ->default('membership')
                            ->required(),
                        Select::make('billing_cycle')
                            ->label('Siklus Pembayaran')
                            ->options([
                                'monthly' => 'Bulanan',
                                'yearly' => 'Tahunan',
                                'one_time' => 'Satu Kali Bayar',
                            ])
                            ->default('monthly')
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi Paket')
                            ->placeholder('Jelaskan fasilitas dan manfaat paket ini...')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Fasilitas, Kunjungan & Kelas')
                    ->description('Atur batasan kunjungan, akses kelas, dan fasilitas personal trainer.')
                    ->schema([
                        Toggle::make('includes_personal_trainer')
                            ->label('Termasuk Personal Trainer')
                            ->helperText('Aktifkan jika paket mencakup sesi dengan Personal Trainer.')
                            ->default(false),
                        Toggle::make('has_visit_limit')
                            ->label('Batasi Jumlah Kunjungan')
                            ->helperText('Jika nonaktif, member dapat berkunjung tanpa batas (unlimited).')
                            ->live()
                            ->default(false),
                        TextInput::make('visit_limit')
                            ->label('Batas Maksimum Kunjungan')
                            ->placeholder('Contoh: 12')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn ($get): bool => (bool) $get('has_visit_limit')),
                        TextInput::make('duration_days')
                            ->label('Masa Aktif / Durasi (Hari)')
                            ->placeholder('Contoh: 30')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        CheckboxList::make('allowed_class_types')
                            ->label('Tipe Kelas yang Diizinkan')
                            ->helperText('Biarkan kosong jika paket ini dapat digunakan untuk semua jenis kelas fitness.')
                            ->options(ClassType::options())
                            ->columns(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Harga & Ketersediaan')
                    ->description('Tentukan harga jual, diskon, dan status ketersediaan paket.')
                    ->schema([
                        TextInput::make('original_price')
                            ->label('Harga Coret / Asli')
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Opsional, untuk menampilkan harga sebelum diskon.'),
                        TextInput::make('discount_percent')
                            ->label('Persentase Diskon')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(0)
                            ->required(),
                        TextInput::make('price')
                            ->label('Harga Jual Akhir')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Nonaktifkan jika paket ini tidak lagi ditawarkan ke member baru. Data member lama tidak akan terpengaruh.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(4),
            ]);
    }
}
