<?php

namespace App\Filament\Resources\MembershipPurchases\Pages;

use App\Filament\Resources\MembershipPurchases\MembershipPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMembershipPurchase extends EditRecord
{
    protected static string $resource = MembershipPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Transaksi')
                ->modalHeading('Hapus Transaksi Membership')
                ->modalDescription('Apakah Anda yakin ingin menghapus data transaksi membership ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal')
                ->successNotificationTitle('Transaksi membership berhasil dihapus'),
        ];
    }
}
