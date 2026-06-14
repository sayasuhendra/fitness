<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassBooking;
use App\Models\Facility;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DemoUserSeeder::class);

        $member = Member::query()->where('member_code', 'MBR000001')->firstOrFail();
        $trainers = $this->seedAkhwatGymTrainers();
        $starter = $this->seedAkhwatGymPackages();
        $this->seedAkhwatGymFacilities();

        MembershipPurchase::query()->updateOrCreate([
            'payment_reference' => 'MID-SEED-MEMBER',
        ], [
            'member_id' => $member->id,
            'membership_package_id' => $starter->id,
            'starts_at' => now()->subDays(2),
            'expires_at' => now()->addDays(28),
            'status' => 'active',
            'includes_personal_trainer' => $starter->includes_personal_trainer,
            'visits_allowed' => $starter->visit_limit,
            'visits_used' => 0,
            'payment_method' => 'midtrans',
            'amount' => $starter->price,
            'payment_reference' => 'MID-SEED-MEMBER',
        ]);

        $classSeeds = $this->akhwatGymScheduleSeeds();
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

        $classes = new Collection;
        foreach ($classSeeds as $seed) {
            $classes->push(FitnessClass::query()->updateOrCreate([
                'name' => $seed['name'],
                'class_date' => $seed['date'],
                'start_time' => $seed['start_time'],
            ], [
                'trainer_id' => $trainers[$seed['trainer']]->id,
                'description' => $seed['description'],
                'class_type' => $seed['class_type'],
                'capacity' => 20,
                'location' => 'Akhwat Gym Studio',
                'is_recurring' => true,
                'recurring_days' => [$seed['day']],
                'recurrence_ends_at' => null,
                'end_time' => $seed['end_time'],
                'is_active' => true,
                'allow_drop_in' => true,
                'drop_in_price' => $seed['drop_in_price'],
                'trainer_addon_price' => 0,
            ]));
        }

        $upcomingClass = $classes->firstWhere('name', 'Zumba Gold');
        if ($upcomingClass !== null) {
            ClassBooking::query()->updateOrCreate([
                'member_id' => $member->id,
                'fitness_class_id' => $upcomingClass->id,
                'booked_for_date' => $upcomingClass->class_date->toDateString(),
            ], [
                'status' => 'confirmed',
                'access_type' => 'membership',
                'personal_trainer_requested' => false,
                'amount' => 0,
                'booked_at' => now()->subHours(3),
                'cancelled_at' => null,
            ]);
        }

        $pastClass = $classes->firstWhere('name', 'Mobility Recovery');
        Attendance::query()->updateOrCreate([
            'member_id' => $member->id,
            'fitness_class_id' => $pastClass?->id,
        ], [
            'check_in_time' => now()->subDay(),
            'status' => 'present',
            'location' => 'Fitness Akhwat Studio',
        ]);

        $this->deactivateLegacyDemoProducts();

        $products = new Collection;
        foreach ($this->productSeeds() as $seed) {
            $category = ProductCategory::query()->updateOrCreate(
                ['slug' => $seed['category_slug']],
                ['name' => $seed['category']],
            );

            $products->push(Product::query()->updateOrCreate([
                'product_category_id' => $category->id,
                'name' => $seed['name'],
            ], [
                'description' => $seed['description'],
                'price' => $seed['price'],
                'stock' => $seed['stock'],
                'image_url' => $seed['image_url'],
                'is_active' => true,
            ]));
        }

        $this->seedOrder($member, $products);
        $this->seedNotifications($member);
    }

    /**
     * @return array<string, Trainer>
     */
    private function seedAkhwatGymTrainers(): array
    {
        $trainerSeeds = [
            'Zin Leila' => ['email' => 'leila@fitnessakhwat.test', 'specialization' => 'Zumba, Zumba Gold'],
            'Teh Wati' => ['email' => 'wati@fitnessakhwat.test', 'specialization' => 'Yoga, Prenatal Yoga'],
            'Pro Lia' => ['email' => 'lia@fitnessakhwat.test', 'specialization' => 'Poundfit'],
            'Teh Novi' => ['email' => 'novi@fitnessakhwat.test', 'specialization' => 'Bomiya'],
            'Teh Uchie' => ['email' => 'uchie@fitnessakhwat.test', 'specialization' => 'Fitdance'],
            'Teh Febby' => ['email' => 'febby@fitnessakhwat.test', 'specialization' => 'Aeromix'],
            'Zin Dewi' => ['email' => 'dewi@fitnessakhwat.test', 'specialization' => 'Zumba'],
            'Zin Gita' => ['email' => 'gita@fitnessakhwat.test', 'specialization' => 'Zumba'],
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

    private function seedAkhwatGymPackages(): MembershipPackage
    {
        $classPackages = [
            [
                'name' => 'Member All Class 4x',
                'description' => 'Paket all class 4x per bulan. Tidak termasuk Gym Class dan Yoga.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 130000,
                'allowed_class_types' => ['zumba', 'zumba_gold', 'aerobic', 'aeromix', 'fitdance', 'bomiya', 'poundfit'],
            ],
            [
                'name' => 'Member All Class 8x',
                'description' => 'Paket all class 8x per bulan. Tidak termasuk Gym Class dan Yoga.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 8,
                'price' => 260000,
                'allowed_class_types' => ['zumba', 'zumba_gold', 'aerobic', 'aeromix', 'fitdance', 'bomiya', 'poundfit'],
            ],
            [
                'name' => 'Gym Visit',
                'description' => 'Sekali datang untuk fasilitas gym.',
                'package_type' => 'one_time',
                'billing_cycle' => 'one_time',
                'includes_personal_trainer' => false,
                'visit_limit' => 1,
                'price' => 32500,
                'allowed_class_types' => ['gym'],
            ],
            [
                'name' => 'Gym Member',
                'description' => 'Membership gym bulanan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => null,
                'price' => 215000,
                'allowed_class_types' => ['gym'],
            ],
            [
                'name' => 'Personal Trainer Visit',
                'description' => 'Sekali sesi personal trainer.',
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
                'description' => 'Paket Zumba 4x per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 120000,
                'allowed_class_types' => ['zumba', 'zumba_gold'],
            ],
            [
                'name' => 'Zumba Member 8x',
                'description' => 'Paket Zumba 8x per bulan.',
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
                'description' => 'Paket Aerobic/Aeromix 4x per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 120000,
                'allowed_class_types' => ['aerobic', 'aeromix'],
            ],
            [
                'name' => 'Aerobic Member 8x',
                'description' => 'Paket Aerobic/Aeromix 8x per bulan.',
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
                'description' => 'Paket Fitdance/Bomiya 4x per bulan.',
                'package_type' => 'membership',
                'billing_cycle' => 'monthly',
                'includes_personal_trainer' => false,
                'visit_limit' => 4,
                'price' => 130000,
                'allowed_class_types' => ['fitdance', 'bomiya'],
            ],
            [
                'name' => 'Fitdance & Bomiya Member 8x',
                'description' => 'Paket Fitdance/Bomiya 8x per bulan.',
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
                'description' => 'Paket Yoga 4x per bulan.',
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
                'description' => 'Cek body fat 2x per bulan.',
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

        $starter = null;

        foreach ($classPackages as $seed) {
            $package = MembershipPackage::query()->updateOrCreate(
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

            if ($seed['name'] === 'Member All Class 4x') {
                $starter = $package;
            }
        }

        return $starter ?? MembershipPackage::query()->where('name', 'Member All Class 4x')->firstOrFail();
    }

    private function seedAkhwatGymFacilities(): void
    {
        foreach ([
            ['name' => 'Free WiFi', 'slug' => 'free-wifi', 'description' => 'Akhwat Gym menyediakan akses WiFi gratis untuk member.', 'icon' => 'wifi'],
            ['name' => 'Hair Dryer', 'slug' => 'hair-dryer', 'description' => 'Hair dryer tersedia untuk digunakan setelah mandi atau latihan.', 'icon' => 'hair-dryer'],
            ['name' => 'Water Heater', 'slug' => 'water-heater', 'description' => 'Kamar mandi dilengkapi water heater.', 'icon' => 'water-heater'],
            ['name' => 'Kamar Mandi', 'slug' => 'kamar-mandi', 'description' => 'Akhwat Gym sediakan sabun dan sampo gratis.', 'icon' => 'shower'],
            ['name' => 'Mushola', 'slug' => 'mushola', 'description' => 'Akhwat Gym sediakan mukena dan sejadah.', 'icon' => 'prayer-mat'],
        ] as $index => $facility) {
            Facility::query()->updateOrCreate(
                ['slug' => $facility['slug']],
                [
                    'name' => $facility['name'],
                    'description' => $facility['description'],
                    'icon' => $facility['icon'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function akhwatGymScheduleSeeds(): array
    {
        $slots = [
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

        return array_map(function (array $slot): array {
            return [
                'day' => $slot['day'],
                'date' => $this->firstScheduleDate($slot['day']),
                'name' => $slot['name'],
                'description' => "{$slot['name']} bersama {$slot['trainer']} sesuai jadwal resmi Akhwat Gym 2025.",
                'class_type' => $slot['type'],
                'trainer' => $slot['trainer'],
                'start_time' => $slot['time'],
                'end_time' => Carbon::createFromFormat('H:i:s', $slot['time'])->addHour()->format('H:i:s'),
                'drop_in_price' => $slot['price'],
            ];
        }, $slots);
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

    /**
     * @return array<int, array<string, mixed>>
     */
    private function productSeeds(): array
    {
        return [
            [
                'category' => 'Makanan Sehat',
                'category_slug' => 'makanan-sehat',
                'name' => 'Protein Overnight Oats',
                'description' => 'Oats tinggi protein dengan buah segar untuk energi sebelum kelas.',
                'price' => 35000,
                'stock' => 30,
                'image_url' => 'https://images.unsplash.com/photo-1517673132405-a56a62b18caf?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'category' => 'Makanan Sehat',
                'category_slug' => 'makanan-sehat',
                'name' => 'Chicken Salad Bowl',
                'description' => 'Salad ayam panggang, sayuran segar, dan dressing ringan.',
                'price' => 52000,
                'stock' => 18,
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'category' => 'Minuman Sehat',
                'category_slug' => 'minuman-sehat',
                'name' => 'Cold Pressed Green Juice',
                'description' => 'Minuman hijau segar dari sayur dan buah tanpa gula tambahan.',
                'price' => 28000,
                'stock' => 42,
                'image_url' => 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'category' => 'Minuman Sehat',
                'category_slug' => 'minuman-sehat',
                'name' => 'Berry Recovery Smoothie',
                'description' => 'Smoothie berry untuk recovery setelah strength training.',
                'price' => 32000,
                'stock' => 24,
                'image_url' => 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'category' => 'Suplemen',
                'category_slug' => 'suplemen',
                'name' => 'Plant Protein Sachet',
                'description' => 'Protein nabati praktis untuk kebutuhan harian member aktif.',
                'price' => 45000,
                'stock' => 35,
                'image_url' => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'category' => 'Suplemen',
                'category_slug' => 'suplemen',
                'name' => 'Electrolyte Hydration Pack',
                'description' => 'Elektrolit rendah gula untuk hidrasi saat latihan.',
                'price' => 39000,
                'stock' => 40,
                'image_url' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=900&q=80',
            ],
        ];
    }

    private function seedOrder(Member $member, Collection $products): void
    {
        $firstProduct = $products->firstWhere('name', 'Cold Pressed Green Juice');
        $secondProduct = $products->firstWhere('name', 'Protein Overnight Oats');

        if ($firstProduct === null || $secondProduct === null) {
            return;
        }

        $order = Order::query()->updateOrCreate([
            'payment_reference' => 'MID-SEED-ORDER',
        ], [
            'member_id' => $member->id,
            'status' => 'completed',
            'payment_method' => 'midtrans',
            'total_price' => ((float) $firstProduct->price * 2) + (float) $secondProduct->price,
            'payment_reference' => 'MID-SEED-ORDER',
        ]);

        foreach ([
            [$firstProduct, 2],
            [$secondProduct, 1],
        ] as [$product, $quantity]) {
            OrderItem::query()->updateOrCreate([
                'order_id' => $order->id,
                'product_id' => $product->id,
            ], [
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'subtotal' => (float) $product->price * $quantity,
            ]);
        }
    }

    private function seedNotifications(Member $member): void
    {
        $user = $member->user;
        $notifications = [
            [
                'id' => '11111111-1111-4111-8111-111111111111',
                'title' => 'Membership aktif',
                'body' => 'Paket Starter Bulanan Anda aktif dan siap dipakai untuk booking kelas.',
                'created_at' => now()->subHours(4),
                'read_at' => null,
            ],
            [
                'id' => '22222222-2222-4222-8222-222222222222',
                'title' => 'Booking kelas berhasil',
                'body' => 'Anda terdaftar di kelas Pilates Pagi. Datang 10 menit lebih awal, ya.',
                'created_at' => now()->subHours(2),
                'read_at' => null,
            ],
            [
                'id' => '33333333-3333-4333-8333-333333333333',
                'title' => 'Pesanan selesai',
                'body' => 'Pesanan healthy shop Anda sudah tercatat sebagai completed.',
                'created_at' => now()->subDay(),
                'read_at' => now()->subHours(8),
            ],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->updateOrInsert([
                'id' => $notification['id'],
            ], [
                'type' => 'demo',
                'notifiable_type' => $user->getMorphClass(),
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => $notification['title'],
                    'body' => $notification['body'],
                ], JSON_THROW_ON_ERROR),
                'read_at' => $notification['read_at'],
                'created_at' => $notification['created_at'],
                'updated_at' => $notification['created_at'],
            ]);
        }
    }

    private function deactivateLegacyDemoProducts(): void
    {
        Product::query()
            ->whereIn('name', [
                'Makanan Sehat 1',
                'Makanan Sehat 2',
                'Makanan Sehat 3',
                'Minuman Sehat 1',
                'Minuman Sehat 2',
                'Minuman Sehat 3',
                'Suplemen 1',
                'Suplemen 2',
                'Suplemen 3',
            ])
            ->update(['is_active' => false]);

        ProductCategory::query()
            ->whereIn('slug', ['healthy-food', 'healthy-drink', 'supplements'])
            ->whereDoesntHave('products')
            ->delete();
    }
}
