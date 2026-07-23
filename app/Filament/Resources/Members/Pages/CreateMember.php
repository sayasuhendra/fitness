<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Models\Member;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

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

                $data['user_id'] = $user->id;
            } else {
                $user = User::query()->findOrFail((int) $data['user_id']);
            }

            Role::findOrCreate('Member', 'web');
            $user->assignRole('Member');
            $data['member_code'] = $this->nextMemberCode();

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

    private function nextMemberCode(): string
    {
        $lastId = (int) Member::query()->max('id');

        return 'MBR'.str_pad((string) ($lastId + 1), 6, '0', STR_PAD_LEFT);
    }
}
