<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateTrainer extends CreateRecord
{
    protected static string $resource = TrainerResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            if (($data['user_mode'] ?? 'existing') === 'new') {
                $user = User::query()->create([
                    'name' => $data['new_user_name'],
                    'email' => $data['new_user_email'],
                    'phone' => $data['new_user_phone'] ?? null,
                    'password' => Hash::make($data['new_user_password']),
                ]);

                Role::findOrCreate('Trainer', 'web');
                $user->assignRole('Trainer');

                $data['user_id'] = $user->id;
            }

            unset(
                $data['user_mode'],
                $data['new_user_name'],
                $data['new_user_email'],
                $data['new_user_phone'],
                $data['new_user_password'],
            );

            return parent::handleRecordCreation($data);
        });
    }
}
