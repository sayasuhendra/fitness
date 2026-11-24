<?php

namespace App\Filament\Resources\Members\Tables;

use App\Models\Member;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Member')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('user.phone')
                    ->label('Nomor HP')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('member_code')
                    ->label('Kode Member')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active_member')
                    ->label('Paket Aktif')
                    ->state(fn ($record): bool => $record->activeMembership() !== null)
                    ->boolean(),
                TextColumn::make('joined_at')
                    ->label('Tanggal Bergabung')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading(fn (Member $record): string => "Hapus Member: {$record->user?->name}")
                    ->modalDescription('Apakah Anda yakin ingin menghapus member ini? Menghapus member akan menghapus seluruh data membership, riwayat booking, absensi, dan pesanan produk terkait.')
                    ->modalSubmitActionLabel('Ya, Hapus Member')
                    ->modalCancelActionLabel('Batal')
                    ->successNotificationTitle('Member berhasil dihapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Member Terpilih')
                        ->modalHeading('Hapus Member Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus member yang dipilih? Seluruh data membership dan riwayat terkait akan ikut terhapus.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->successNotificationTitle('Member terpilih berhasil dihapus'),
                ]),
            ]);
    }
}
