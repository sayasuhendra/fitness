<?php

namespace App\Filament\Resources\MembershipPackages\Pages;

use App\Filament\Resources\MembershipPackages\MembershipPackageResource;
use App\Models\MembershipPackage;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMembershipPackage extends EditRecord
{
    protected static string $resource = MembershipPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Paket')
                ->modalHeading(fn (MembershipPackage $record): string => "Hapus Paket: {$record->name}")
                ->modalDescription('Apakah Anda yakin ingin menghapus paket membership ini? Jika paket sudah memiliki data transaksi/member, sistem akan membatalkan penghapusan demi menjaga integritas data.')
                ->modalSubmitActionLabel('Ya, Hapus Paket')
                ->modalCancelActionLabel('Batal')
                ->before(function (DeleteAction $action, MembershipPackage $record) {
                    $purchaseCount = $record->purchases()->count();

                    if ($purchaseCount > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Paket Membership Tidak Dapat Dihapus')
                            ->body("Paket \"{$record->name}\" tidak dapat dihapus karena masih digunakan oleh {$purchaseCount} riwayat transaksi / member terdaftar.\n\nSaran: Anda dapat menonaktifkan paket ini (ubah status Aktif menjadi Tidak Aktif) di form ini lalu klik Simpan, agar paket tidak dapat dibeli lagi oleh member baru.")
                            ->persistent()
                            ->send();

                        $action->halt();
                    }
                })
                ->successNotificationTitle('Paket membership berhasil dihapus'),
        ];
    }
}
