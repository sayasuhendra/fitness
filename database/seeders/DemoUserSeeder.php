<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUserSeeder extends Seeder
{
    public const PASSWORD = 'password123';

    public function run(): void
    {
        foreach (['super_admin', 'Member', 'Trainer'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $admin = $this->upsertUser(
            name: 'Admin Fitness Akhwat',
            email: 'admin@fitnessakhwat.test',
            phone: '081100000001',
        );

        $memberUser = $this->upsertUser(
            name: 'Aisyah Member',
            email: 'member@fitnessakhwat.test',
            phone: '081100000002',
        );

        $trainerUser = $this->upsertUser(
            name: 'Fatimah Trainer',
            email: 'trainer@fitnessakhwat.test',
            phone: '081100000003',
        );

        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('super_admin');
            $memberUser->assignRole('Member');
            $trainerUser->assignRole('Trainer');
        }

        Member::query()->updateOrCreate(
            ['user_id' => $memberUser->id],
            [
                'member_code' => 'MBR000001',
                'joined_at' => now()->subDays(10)->toDateString(),
            ],
        );

        Trainer::query()->updateOrCreate(
            ['user_id' => $trainerUser->id],
            [
                'specialization' => 'Pilates dan Strength',
                'bio' => 'Trainer akhwat tersertifikasi yang fokus pada gerakan aman dan berkelanjutan.',
                'is_active' => true,
            ],
        );

        $this->command?->info('Demo users ready:');
        $this->command?->line('  admin@fitnessakhwat.test / '.self::PASSWORD);
        $this->command?->line('  member@fitnessakhwat.test / '.self::PASSWORD);
        $this->command?->line('  trainer@fitnessakhwat.test / '.self::PASSWORD);
    }

    private function upsertUser(string $name, string $email, string $phone): User
    {
        return User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => $phone,
                'password' => Hash::make(self::PASSWORD),
            ],
        );
    }
}
