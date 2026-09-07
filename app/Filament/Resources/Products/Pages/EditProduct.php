<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false)
                ->modalHeading('Hapus Produk')
                ->modalDescription('Apakah Anda yakin ingin menghapus produk ini? Produk yang dihapus tidak akan ditampilkan lagi di toko, namun riwayat transaksi pesanan lama tetap aman tersimpan.')
                ->modalSubmitActionLabel('Ya, Hapus Produk'),
            RestoreAction::make()
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false),
        ];
    }
}
