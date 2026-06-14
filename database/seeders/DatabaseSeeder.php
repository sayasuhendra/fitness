<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Trainer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        $trainer = Trainer::query()->whereHas('user', function ($query): void {
            $query->where('email', 'trainer@fitnessakhwat.test');
        })->firstOrFail();

        $starter = MembershipPackage::query()->updateOrCreate([
            'name' => 'Starter Bulanan',
        ], [
            'description' => 'Akses kelas reguler dan booking member selama 30 hari.',
            'duration_days' => 30,
            'price' => 350000,
            'is_active' => true,
        ]);
        MembershipPackage::query()->updateOrCreate([
            'name' => 'Komitmen Tiga Bulan',
        ], [
            'description' => 'Pilihan terbaik untuk latihan mingguan yang konsisten selama 90 hari.',
            'duration_days' => 90,
            'price' => 900000,
            'is_active' => true,
        ]);
        MembershipPackage::query()->updateOrCreate([
            'name' => 'Akhwat Tahunan',
        ], [
            'description' => 'Akses setahun penuh dengan prioritas booking.',
            'duration_days' => 365,
            'price' => 3200000,
            'is_active' => true,
        ]);

        MembershipPurchase::query()->updateOrCreate([
            'payment_reference' => 'MID-SEED-MEMBER',
        ], [
            'member_id' => $member->id,
            'membership_package_id' => $starter->id,
            'starts_at' => now()->subDays(2),
            'expires_at' => now()->addDays(28),
            'status' => 'active',
            'payment_method' => 'midtrans',
            'amount' => $starter->price,
            'payment_reference' => 'MID-SEED-MEMBER',
        ]);

        foreach (['Pilates Pagi', 'Strength untuk Pemula', 'Yoga Flow'] as $index => $name) {
            FitnessClass::query()->updateOrCreate([
                'name' => $name,
                'class_date' => now()->addDays($index + 1)->toDateString(),
            ], [
                'trainer_id' => $trainer->id,
                'description' => 'Kelas khusus akhwat dengan teknik terpandu dan progres aman.',
                'capacity' => 12,
                'location' => 'Studio '.chr(65 + $index),
                'start_time' => sprintf('%02d:00:00', 8 + $index),
                'end_time' => sprintf('%02d:00:00', 9 + $index),
                'is_active' => true,
            ]);
        }

        Attendance::query()->updateOrCreate([
            'member_id' => $member->id,
            'fitness_class_id' => FitnessClass::query()->first()->id,
        ], [
            'check_in_time' => now()->subDay(),
            'status' => 'present',
            'location' => 'Fitness Akhwat Studio',
        ]);

        foreach ([
            ['Makanan Sehat', 'makanan-sehat'],
            ['Minuman Sehat', 'minuman-sehat'],
            ['Suplemen', 'suplemen'],
        ] as [$name, $slug]) {
            $category = ProductCategory::query()->updateOrCreate(['slug' => $slug], ['name' => $name]);

            foreach (range(1, 3) as $number) {
                Product::query()->updateOrCreate([
                    'product_category_id' => $category->id,
                    'name' => "{$name} {$number}",
                ], [
                    'description' => "Produk {$name} pilihan untuk member Fitness Akhwat.",
                    'price' => 25000 * $number,
                    'stock' => 20 * $number,
                    'image_url' => null,
                    'is_active' => true,
                ]);
            }
        }
    }
}
