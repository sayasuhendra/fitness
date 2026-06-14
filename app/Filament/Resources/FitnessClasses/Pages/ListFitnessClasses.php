<?php

namespace App\Filament\Resources\FitnessClasses\Pages;

use App\Filament\Resources\FitnessClasses\FitnessClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFitnessClasses extends ListRecords
{
    protected static string $resource = FitnessClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
