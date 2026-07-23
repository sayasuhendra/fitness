<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\User;
use App\Support\AdminShift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUserSeeder extends Seeder
{
    public const PASSWORD = 'password123';

    public function run(): void
    {
        foreach (AdminRoleSeeder::ADMIN_ROLES as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        foreach (['Member', 'Trainer'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $admin = $this->upsertUser(
            name: 'Admin Akhwat Gym',
            email: 'admin@akhwatgym.test',
            phone: '081100000001',
        );

        $owner = $this->upsertUser(
            name: 'Owner Akhwat Gym',
            email: 'owner@akhwatgym.test',
            phone: '081100000004',
        );

        $locationAdmin = $this->upsertUser(
            name: 'Admin Lokasi Akhwat Gym',
            email: 'admin.lokasi@akhwatgym.test',
            phone: '081100000005',
        );

        $locationAdminShift1 = $this->upsertUser(
            name: 'Admin Lokasi Shift 1',
            email: 'admin.lokasi.shift1@akhwatgym.test',
            phone: '081100000006',
            adminShift: AdminShift::SHIFT_1,
        );

        $locationAdminShift2 = $this->upsertUser(
            name: 'Admin Lokasi Shift 2',
            email: 'admin.lokasi.shift2@akhwatgym.test',
            phone: '081100000007',
            adminShift: AdminShift::SHIFT_2,
        );

        $memberUser = $this->upsertUser(
            name: 'Aisyah Member',
            email: 'member@akhwatgym.test',
            phone: '081100000002',
        );

        $trainerUser = $this->upsertUser(
            name: 'Fatimah Trainer',
            email: 'trainer@akhwatgym.test',
            phone: '081100000003',
        );

        if (method_exists($admin, 'assignRole')) {
            $owner->assignRole('Owner');
            $admin->assignRole('Super admin');
            $locationAdmin->assignRole('Admin di lokasi');
            $locationAdminShift1->assignRole('Admin di lokasi');
            $locationAdminShift2->assignRole('Admin di lokasi');
            $memberUser->assignRole('Member');
            $trainerUser->assignRole('Trainer');
        }

        Member::query()->updateOrCreate(
            ['member_code' => 'MBR000001'],
            [
                'user_id' => $memberUser->id,
                'joined_at' => now()->subDays(10)->toDateString(),
            ],
        );

        Trainer::query()->updateOrCreate(
            ['user_id' => $trainerUser->id],
            [
                'specialization' => 'Pilates dan Strength',
                'whatsapp_number' => '6281100000003',
                'bio' => 'Trainer akhwat tersertifikasi yang fokus pada gerakan aman dan berkelanjutan.',
                'is_active' => true,
            ],
        );

        $this->command?->info('Demo users ready:');
        $this->command?->line('  owner@akhwatgym.test / '.self::PASSWORD);
        $this->command?->line('  admin@akhwatgym.test / '.self::PASSWORD);
        $this->command?->line('  admin.lokasi@akhwatgym.test / '.self::PASSWORD);
        $this->command?->line('  admin.lokasi.shift1@akhwatgym.test / '.self::PASSWORD);
        $this->command?->line('  admin.lokasi.shift2@akhwatgym.test / '.self::PASSWORD);
        $this->command?->line('  member@akhwatgym.test / '.self::PASSWORD);
        $this->command?->line('  trainer@akhwatgym.test / '.self::PASSWORD);
    }

    private function upsertUser(string $name, string $email, string $phone, ?string $adminShift = null): User
    {
        return User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => $phone,
                'admin_shift' => $adminShift,
                'password' => Hash::make(self::PASSWORD),
            ],
        );
    }
}
