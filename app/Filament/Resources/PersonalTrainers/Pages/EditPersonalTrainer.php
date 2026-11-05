<?php

namespace App\Filament\Resources\PersonalTrainers\Pages;

use App\Filament\Resources\PersonalTrainers\PersonalTrainerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPersonalTrainer extends EditRecord
{
    protected static string $resource = PersonalTrainerResource::class;

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
        return [DeleteAction::make()];
    }
}
