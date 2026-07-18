<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FitnessClass;
use App\Models\MembershipPackage;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AkhwatGymPackageScheduleSeeder extends Seeder
{
    /**
     * Seed official Akhwat Gym package price list and weekly class schedule.
     */
    public function run(): void
    {
        $trainers = $this->seedTrainers();

        $this->seedPackages();
        $this->seedSchedules($trainers);

        $this->command?->info('Akhwat Gym packages and weekly schedules are ready.');
    }

    /**
     * @return array<string, Trainer>
     */
    private function seedTrainers(): array
    {
        Role::findOrCreate('Trainer', 'web');

        $trainerSeeds = [
            'Zin Leila' => ['email' => 'leila@akhwatgym.test', 'specialization' => 'Zumba, Zumba Gold'],
            'Teh Wati' => ['email' => 'wati@akhwatgym.test', 'specialization' => 'Yoga, Prenatal Yoga'],
            'Pro Lia' => ['email' => 'lia@akhwatgym.test', 'specialization' => 'Poundfit'],
            'Teh Novi' => ['email' => 'novi@akhwatgym.test', 'specialization' => 'Bomiya'],
            'Teh Uchie' => ['email' => 'uchie@akhwatgym.test', 'specialization' => 'Fitdance'],
            'Teh Febby' => ['email' => 'febby@akhwatgym.test', 'specialization' => 'Aeromix'],
            'Zin Dewi' => ['email' => 'dewi@akhwatgym.test', 'specialization' => 'Zumba'],
            'Zin Gita' => ['email' => 'gita@akhwatgym.test', 'specialization' => 'Zumba'],
        ];

        $trainers = [];

        foreach ($trainerSeeds as $name => $seed) {
            $user = User::query()->updateOrCreate(
                ['email' => $seed['email']],
                [
                    'name' => $name,
                    'phone' => null,
                    'password' => Hash::make(DemoUserSeeder::PASSWORD),
                ],
            );

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('Trainer');
            }

            $trainers[$name] = Trainer::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $seed['specialization'],
                    'bio' => "{$name} adalah instruktur Akhwat Gym untuk kelas {$seed['specialization']}.",
                    'is_active' => true,
                ],
            );
        }

        return $trainers;
    }

    private function seedPackages(): void
    {
        MembershipPackage::query()
            ->whereIn('name', [
                'Starter Bulanan',
                'Personal Trainer Bulanan',
                'Akhwat Tahunan',
                'One-Time Visit',
                'One-Time Visit + Personal Trainer',
                'Komitmen Tiga Bulan',
            ])
            ->update(['is_active' => false]);

        foreach ($this->packageSeeds() as $seed) {
            MembershipPackage::query()->updateOrCreate(
                ['name' => $seed['name']],
                [
                    'description' => $seed['description'],
                    'package_type' => $seed['package_type'],
                    'billing_cycle' => $seed['billing_cycle'],
                    'includes_personal_trainer' => $seed['includes_personal_trainer'],
                    'has_visit_limit' => $seed['visit_limit'] !== null,
                    'visit_limit' => $seed['visit_limit'],
                    'allowed_class_types' => $seed['allowed_class_types'],
                    'duration_days' => $seed['billing_cycle'] === 'one_time' ? 1 : 30,
                    'price' => $seed['price'],
                    'discount_percent' => 0,
                    'original_price' => null,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function packageSeeds(): array
    {
        return [
            [
                'name' => 'Member All Class 4x',
                'description' => 'Paket all class 4 kali kunjungan per bulan. Tidak termasuk Gym Class dan Yoga.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 130000,
                'allowed_class_types' => ['zumba', 'zumba_gold', 'aerobic', 'aeromix', 'fitdance', 'bomiya', 'poundfit'],
            ],
            [
                'name' => 'Member All Class 8x',
                'description' => 'Paket all class 8 kali kunjungan per bulan. Tidak termasuk Gym Class dan Yoga.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 8,
                'price' => 260000,
                'allowed_class_types' => ['zumba', 'zumba_gold', 'aerobic', 'aeromix', 'fitdance', 'bomiya', 'poundfit'],
            ],
            [
                'name' => 'Gym Visit',
                'description' => 'Sekali datang untuk memakai fasilitas gym.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 32500,
                'allowed_class_types' => ['gym'],
            ],
            [
                'name' => 'Gym Member',
                'description' => 'Membership gym bulanan tanpa personal trainer.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => null,
                'price' => 215000,
                'allowed_class_types' => ['gym'],
            ],
            [
                'name' => 'Personal Trainer Visit',
                'description' => 'Sekali sesi latihan dengan personal trainer.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => true,
                'visit_limit' => 1,
                'price' => 80000,
                'allowed_class_types' => ['personal_trainer'],
            ],
            [
                'name' => 'Personal Trainer Member',
                'description' => 'Membership personal trainer bulanan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => true,
                'visit_limit' => null,
                'price' => 445000,
                'allowed_class_types' => ['personal_trainer'],
            ],
            [
                'name' => 'Zumba Visit',
                'description' => 'Sekali datang untuk kelas Zumba atau Zumba Gold.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 35000,
                'allowed_class_types' => ['zumba', 'zumba_gold'],
            ],
            [
                'name' => 'Zumba Member 4x',
                'description' => 'Paket Zumba 4 kali kunjungan per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 120000,
                'allowed_class_types' => ['zumba', 'zumba_gold'],
            ],
            [
                'name' => 'Zumba Member 8x',
                'description' => 'Paket Zumba 8 kali kunjungan per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 8,
                'price' => 230000,
                'allowed_class_types' => ['zumba', 'zumba_gold'],
            ],
            [
                'name' => 'Aerobic Visit',
                'description' => 'Sekali datang untuk kelas Aerobic atau Aeromix.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 32500,
                'allowed_class_types' => ['aerobic', 'aeromix'],
            ],
            [
                'name' => 'Aerobic Member 4x',
                'description' => 'Paket Aerobic/Aeromix 4 kali kunjungan per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 120000,
                'allowed_class_types' => ['aerobic', 'aeromix'],
            ],
            [
                'name' => 'Aerobic Member 8x',
                'description' => 'Paket Aerobic/Aeromix 8 kali kunjungan per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 8,
                'price' => 230000,
                'allowed_class_types' => ['aerobic', 'aeromix'],
            ],
            [
                'name' => 'Fitdance & Bomiya Visit',
                'description' => 'Sekali datang untuk kelas Fitdance atau Bomiya.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 37500,
                'allowed_class_types' => ['fitdance', 'bomiya'],
            ],
            [
                'name' => 'Fitdance & Bomiya Member 4x',
                'description' => 'Paket Fitdance/Bomiya 4 kali kunjungan per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 130000,
                'allowed_class_types' => ['fitdance', 'bomiya'],
            ],
            [
                'name' => 'Fitdance & Bomiya Member 8x',
                'description' => 'Paket Fitdance/Bomiya 8 kali kunjungan per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 8,
                'price' => 260000,
                'allowed_class_types' => ['fitdance', 'bomiya'],
            ],
            [
                'name' => 'Yoga Visit',
                'description' => 'Sekali datang untuk kelas Yoga.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 52500,
                'allowed_class_types' => ['yoga', 'prenatal_yoga'],
            ],
            [
                'name' => 'Yoga Member 4x',
                'description' => 'Paket Yoga 4 kali kunjungan per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 200000,
                'allowed_class_types' => ['yoga', 'prenatal_yoga'],
            ],
            [
                'name' => 'Poundfit Visit',
                'description' => 'Sekali datang untuk kelas Poundfit.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 50000,
                'allowed_class_types' => ['poundfit'],
            ],
            [
                'name' => 'Body Fat Check',
                'description' => 'Paket cek body fat 2 kali per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 2,
                'price' => 15000,
                'allowed_class_types' => ['body_fat'],
            ],
            [
                'name' => 'ADM New Member',
                'description' => 'Biaya administrasi member baru.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 15000,
                'allowed_class_types' => [],
            ],
        ];
    }

    /**
     * @param  array<string, Trainer>  $trainers
     */
    private function seedSchedules(array $trainers): void
    {
        FitnessClass::query()
            ->whereIn('name', [
                'Pilates Pagi',
                'Strength untuk Pemula',
                'Yoga Flow',
                'Zumba Akhwat',
                'Circuit Training',
                'Mobility Recovery',
            ])
            ->update(['is_active' => false]);

        FitnessClass::query()
            ->whereIn('name', array_values(array_unique(array_column($this->scheduleSeeds(), 'name'))))
            ->whereDate('class_date', '>=', '2025-01-01')
            ->whereDate('class_date', '<=', '2025-01-07')
            ->update(['is_active' => false]);

        foreach ($this->scheduleSeeds() as $seed) {
            $classDate = $this->firstScheduleDate($seed['day']);
            $class = FitnessClass::query()
                ->where('name', $seed['name'])
                ->whereDate('class_date', $classDate)
                ->where('start_time', $seed['time'])
                ->oldest('id')
                ->first() ?? new FitnessClass([
                    'name' => $seed['name'],
                    'class_date' => $classDate,
                    'start_time' => $seed['time'],
                ]);

            $class->fill([
                'trainer_id' => $trainers[$seed['trainer']]->id,
                'description' => "{$seed['name']} bersama {$seed['trainer']} sesuai jadwal resmi Akhwat Gym 2025.",
                'class_type' => $seed['type'],
                'capacity' => 20,
                'location' => 'Akhwat Gym Studio',
                'is_recurring' => true,
                'recurring_days' => [$seed['day']],
                'recurrence_ends_at' => null,
                'end_time' => Carbon::createFromFormat('H:i:s', $seed['time'])->addHour()->format('H:i:s'),
                'is_active' => true,
                'allow_drop_in' => true,
                'drop_in_price' => $seed['price'],
                'trainer_addon_price' => 0,
            ]);

            $class->save();
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function scheduleSeeds(): array
    {
        return [
            ['day' => 'monday', 'time' => '08:00:00', 'name' => 'Zumba Gold', 'type' => 'zumba_gold', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'monday', 'time' => '10:00:00', 'name' => 'Yoga', 'type' => 'yoga', 'trainer' => 'Teh Wati', 'price' => 52500],
            ['day' => 'monday', 'time' => '16:15:00', 'name' => 'Poundfit', 'type' => 'poundfit', 'trainer' => 'Pro Lia', 'price' => 50000],
            ['day' => 'tuesday', 'time' => '08:30:00', 'name' => 'Bomiya', 'type' => 'bomiya', 'trainer' => 'Teh Novi', 'price' => 37500],
            ['day' => 'tuesday', 'time' => '10:15:00', 'name' => 'Fitdance', 'type' => 'fitdance', 'trainer' => 'Teh Uchie', 'price' => 37500],
            ['day' => 'tuesday', 'time' => '16:15:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'tuesday', 'time' => '17:30:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'wednesday', 'time' => '08:30:00', 'name' => 'Aeromix', 'type' => 'aeromix', 'trainer' => 'Teh Febby', 'price' => 32500],
            ['day' => 'wednesday', 'time' => '15:00:00', 'name' => 'Fitdance', 'type' => 'fitdance', 'trainer' => 'Teh Uchie', 'price' => 37500],
            ['day' => 'wednesday', 'time' => '16:15:00', 'name' => 'Yoga', 'type' => 'yoga', 'trainer' => 'Teh Wati', 'price' => 52500],
            ['day' => 'wednesday', 'time' => '17:30:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Dewi', 'price' => 35000],
            ['day' => 'thursday', 'time' => '08:00:00', 'name' => 'Zumba Gold', 'type' => 'zumba_gold', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'thursday', 'time' => '17:10:00', 'name' => 'Poundfit', 'type' => 'poundfit', 'trainer' => 'Pro Lia', 'price' => 50000],
            ['day' => 'friday', 'time' => '08:30:00', 'name' => 'Bomiya', 'type' => 'bomiya', 'trainer' => 'Teh Novi', 'price' => 37500],
            ['day' => 'friday', 'time' => '16:15:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'friday', 'time' => '17:30:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'saturday', 'time' => '07:15:00', 'name' => 'Zumba Gold', 'type' => 'zumba_gold', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'saturday', 'time' => '08:15:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'saturday', 'time' => '10:00:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Gita', 'price' => 35000],
            ['day' => 'saturday', 'time' => '15:00:00', 'name' => 'Yoga', 'type' => 'yoga', 'trainer' => 'Teh Wati', 'price' => 52500],
            ['day' => 'saturday', 'time' => '16:30:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Dewi', 'price' => 35000],
            ['day' => 'sunday', 'time' => '07:15:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'sunday', 'time' => '08:30:00', 'name' => 'Zumba', 'type' => 'zumba', 'trainer' => 'Zin Leila', 'price' => 35000],
            ['day' => 'sunday', 'time' => '15:00:00', 'name' => 'Yoga', 'type' => 'yoga', 'trainer' => 'Teh Wati', 'price' => 52500],
            ['day' => 'sunday', 'time' => '16:30:00', 'name' => 'Yoga Prenatal', 'type' => 'prenatal_yoga', 'trainer' => 'Teh Wati', 'price' => 52500],
        ];
    }

    private function firstScheduleDate(string $day): string
    {
        return match ($day) {
            'monday' => '2025-01-06',
            'tuesday' => '2025-01-07',
            'wednesday' => '2025-01-01',
            'thursday' => '2025-01-02',
            'friday' => '2025-01-03',
            'saturday' => '2025-01-04',
            'sunday' => '2025-01-05',
            default => '2025-01-01',
        };
    }
}
