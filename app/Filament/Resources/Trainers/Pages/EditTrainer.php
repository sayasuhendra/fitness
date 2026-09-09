<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainer extends EditRecord
{
    protected static string $resource = TrainerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false)
                ->modalHeading('Hapus Instruktur')
                ->modalDescription('Apakah Anda yakin ingin menghapus instruktur ini? Instruktur dan kelas yang diajar akan dinonaktifkan (soft delete), namun riwayat booking dan absensi member tetap tersimpan dengan aman.')
                ->modalSubmitActionLabel('Ya, Hapus Instruktur'),
            RestoreAction::make()
                ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false),
        ];
    }
}
