<?php

namespace App\Filament\Resources\PersonalTrainerSessions\Pages;

use App\Filament\Resources\PersonalTrainerSessions\PersonalTrainerSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonalTrainerSessions extends ListRecords
{
    protected static string $resource = PersonalTrainerSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
