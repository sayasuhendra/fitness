<?php

namespace App\Filament\Resources\MembershipPackages\Pages;

use App\Filament\Resources\MembershipPackages\MembershipPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMembershipPackage extends EditRecord
{
    protected static string $resource = MembershipPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
