<?php

namespace App\Filament\Resources\ProductCategories\Pages;

use App\Filament\Resources\ProductCategories\ProductCategoryResource;
use App\Models\ProductCategory;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Kategori')
                ->modalHeading(fn (ProductCategory $record): string => "Hapus Kategori: {$record->name}")
                ->modalDescription('Apakah Anda yakin ingin menghapus kategori produk ini? Jika kategori masih memiliki produk terdaftar, sistem akan membatalkan penghapusan demi menjaga integritas data.')
                ->modalSubmitActionLabel('Ya, Hapus Kategori')
                ->modalCancelActionLabel('Batal')
                ->before(function (DeleteAction $action, ProductCategory $record) {
                    $productCount = $record->products()->count();

                    if ($productCount > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Kategori Tidak Dapat Dihapus')
                            ->body("Kategori \"{$record->name}\" tidak dapat dihapus karena masih memiliki {$productCount} produk terdaftar.\n\nSaran: Silakan pindahkan atau hapus produk di dalam kategori ini terlebih dahulu.")
                            ->persistent()
                            ->send();

                        $action->halt();
                    }
                })
                ->successNotificationTitle('Kategori produk berhasil dihapus'),
        ];
    }
}
