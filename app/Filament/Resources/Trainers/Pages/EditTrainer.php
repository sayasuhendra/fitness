<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTrainer extends EditRecord
{
    protected static string $resource = TrainerResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (isset($data['user_name']) || array_key_exists('user_phone', $data)) {
            $userUpdates = [];
            if (isset($data['user_name'])) {
                $userUpdates['name'] = $data['user_name'];
            }
            if (array_key_exists('user_phone', $data)) {
                $userUpdates['phone'] = $data['user_phone'];
            }
            if (! empty($userUpdates)) {
                $record->user?->update($userUpdates);
            }
        }

        unset($data['user_name'], $data['user_phone']);

        return parent::handleRecordUpdate($record, $data);
    }

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
