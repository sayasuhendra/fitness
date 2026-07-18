<?php

namespace App\Filament\Resources\PersonalTrainerSessions\Pages;

use App\Filament\Resources\PersonalTrainerSessions\PersonalTrainerSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonalTrainerSession extends EditRecord
{
    protected static string $resource = PersonalTrainerSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
