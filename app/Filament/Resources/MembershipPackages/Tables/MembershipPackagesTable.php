<?php

namespace App\Filament\Resources\MembershipPackages\Tables;

use App\Models\MembershipPackage;
use App\Support\ClassType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class MembershipPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('package_type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'membership' => 'Membership',
                        'one_time' => 'Kunjungan Sekali',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'membership' => 'primary',
                        'one_time' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('billing_cycle')
                    ->label('Siklus')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan',
                        'one_time' => 'Satu Kali',
                        default => ucfirst($state),
                    })
                    ->badge(),
                IconColumn::make('includes_personal_trainer')
                    ->label('Termasuk PT')
                    ->boolean(),
                TextColumn::make('visit_limit')
                    ->label('Batas Visit')
                    ->formatStateUsing(fn ($state, $record): string => $record->has_visit_limit ? "{$state} visit" : 'Tanpa Batas')
                    ->badge()
                    ->color(fn ($state, $record): string => $record->has_visit_limit ? 'warning' : 'success'),
                TextColumn::make('allowed_class_types')
                    ->label('Akses Kelas')
                    ->formatStateUsing(function ($state, $record): string {
                        if ($record->allowed_class_types === null || $record->allowed_class_types === []) {
                            return 'Semua Kelas';
                        }

                        return implode(', ', array_map(fn ($type) => ClassType::label($type), $record->allowed_class_types));
                    })
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('duration_days')
                    ->label('Durasi (Hari)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount_percent')
                    ->label('Diskon')
                    ->suffix('%')
                    ->numeric(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('package_type')
                    ->label('Tipe Paket')
                    ->options([
                        'membership' => 'Membership',
                        'one_time' => 'Kunjungan Sekali (One-time)',
                    ]),
                SelectFilter::make('billing_cycle')
                    ->label('Siklus Pembayaran')
                    ->options([
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan',
                        'one_time' => 'Satu Kali',
                    ]),
                TernaryFilter::make('includes_personal_trainer')
                    ->label('Termasuk Personal Trainer'),
                TernaryFilter::make('is_active')
                    ->label('Status Ketersediaan')
                    ->trueLabel('Aktif (Tersedia)')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading(fn (MembershipPackage $record): string => "Hapus Paket: {$record->name}")
                    ->modalDescription('Apakah Anda yakin ingin menghapus paket membership ini? Jika paket sudah memiliki data transaksi/member terdaftar, sistem akan membatalkan penghapusan demi menjaga integritas data.')
                    ->modalSubmitActionLabel('Ya, Hapus Paket')
                    ->modalCancelActionLabel('Batal')
                    ->before(function (DeleteAction $action, MembershipPackage $record) {
                        $purchaseCount = $record->purchases()->count();

                        if ($purchaseCount > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Paket Membership Tidak Dapat Dihapus')
                                ->body("Paket \"{$record->name}\" tidak dapat dihapus karena masih digunakan oleh {$purchaseCount} riwayat transaksi / member terdaftar.\n\nSaran: Anda dapat menonaktifkan paket ini (ubah status Aktif menjadi Tidak Aktif) melalui tombol Ubah agar paket tidak dapat dibeli lagi oleh member baru, sementara riwayat member lama tetap aman tersimpan.")
                                ->persistent()
                                ->send();

                            $action->halt();
                        }
                    })
                    ->successNotificationTitle('Paket membership berhasil dihapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Paket Terpilih')
                        ->modalHeading('Hapus Paket Membership Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus paket membership yang dipilih? Paket yang sudah memiliki data transaksi/member tidak akan dihapus demi menjaga integritas data.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->action(function (Collection $records, DeleteBulkAction $action) {
                            $deletedCount = 0;
                            $blockedPackages = [];

                            foreach ($records as $record) {
                                $purchaseCount = $record->purchases()->count();

                                if ($purchaseCount > 0) {
                                    $blockedPackages[] = "• \"{$record->name}\" ({$purchaseCount} transaksi/member)";
                                } else {
                                    $record->delete();
                                    $deletedCount++;
                                }
                            }

                            if (! empty($blockedPackages)) {
                                $list = implode("\n", $blockedPackages);
                                Notification::make()
                                    ->danger()
                                    ->title('Beberapa Paket Tidak Dapat Dihapus')
                                    ->body("Paket berikut tidak dapat dihapus karena masih memiliki riwayat transaksi/member:\n\n{$list}\n\nSaran: Silakan ubah status paket menjadi 'Tidak Aktif' melalui menu Ubah agar paket tidak lagi ditawarkan kepada member baru.")
                                    ->persistent()
                                    ->send();
                            }

                            if ($deletedCount > 0) {
                                Notification::make()
                                    ->success()
                                    ->title('Penghapusan Berhasil')
                                    ->body("Sebanyak {$deletedCount} paket membership yang tidak memiliki transaksi berhasil dihapus.")
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }
}
