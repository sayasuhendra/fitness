<?php

namespace App\Filament\Resources\FitnessClasses\Pages;

use App\Filament\Resources\FitnessClasses\FitnessClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFitnessClass extends EditRecord
{
    protected static string $resource = FitnessClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
