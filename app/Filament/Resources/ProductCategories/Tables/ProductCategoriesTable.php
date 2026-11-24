<?php

namespace App\Filament\Resources\ProductCategories\Tables;

use App\Models\ProductCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('products_count')
                    ->label('Jumlah Produk')
                    ->counts('products')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'primary' : 'gray')
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
                    ->modalHeading(fn (ProductCategory $record): string => "Hapus Kategori: {$record->name}")
                    ->modalDescription('Apakah Anda yakin ingin menghapus kategori produk ini? Jika kategori masih memiliki produk terdaftar, penghapusan akan dibatalkan demi keamanan data.')
                    ->modalSubmitActionLabel('Ya, Hapus Kategori')
                    ->modalCancelActionLabel('Batal')
                    ->before(function (DeleteAction $action, ProductCategory $record) {
                        $productCount = $record->products()->count();

                        if ($productCount > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Kategori Tidak Dapat Dihapus')
                                ->body("Kategori \"{$record->name}\" tidak dapat dihapus karena masih memiliki {$productCount} produk terdaftar. Silakan hapus atau pindahkan produk tersebut ke kategori lain terlebih dahulu.")
                                ->persistent()
                                ->send();

                            $action->halt();
                        }
                    })
                    ->successNotificationTitle('Kategori produk berhasil dihapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Kategori Terpilih')
                        ->modalHeading('Hapus Kategori Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus kategori produk yang dipilih? Kategori yang masih memiliki produk tidak akan dihapus demi menjaga integritas data.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->action(function (Collection $records, DeleteBulkAction $action) {
                            $deletedCount = 0;
                            $blockedCategories = [];

                            foreach ($records as $record) {
                                $productCount = $record->products()->count();

                                if ($productCount > 0) {
                                    $blockedCategories[] = "• \"{$record->name}\" ({$productCount} produk)";
                                } else {
                                    $record->delete();
                                    $deletedCount++;
                                }
                            }

                            if (! empty($blockedCategories)) {
                                $list = implode("\n", $blockedCategories);
                                Notification::make()
                                    ->danger()
                                    ->title('Beberapa Kategori Tidak Dapat Dihapus')
                                    ->body("Kategori berikut tidak dapat dihapus karena masih memiliki produk terdaftar:\n\n{$list}\n\nSaran: Silakan pindahkan atau hapus produk di dalam kategori tersebut terlebih dahulu.")
                                    ->persistent()
                                    ->send();
                            }

                            if ($deletedCount > 0) {
                                Notification::make()
                                    ->success()
                                    ->title('Penghapusan Berhasil')
                                    ->body("Sebanyak {$deletedCount} kategori produk yang kosong berhasil dihapus.")
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }
}
