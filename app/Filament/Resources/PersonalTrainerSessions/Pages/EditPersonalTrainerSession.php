<?php

namespace App\Filament\Resources\PersonalTrainerSessions\Pages;

use App\Filament\Resources\PersonalTrainerSessions\PersonalTrainerSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditPersonalTrainerSession extends EditRecord
{
    protected static string $resource = PersonalTrainerSessionResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
