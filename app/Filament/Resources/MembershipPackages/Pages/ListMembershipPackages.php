<?php

namespace App\Filament\Resources\MembershipPackages\Pages;

use App\Filament\Resources\MembershipPackages\MembershipPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMembershipPackages extends ListRecords
{
    protected static string $resource = MembershipPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
