<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['super_admin', 'Member', 'Trainer'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $admin = User::factory()->create([
            'name' => 'Fitness Akhwat Admin',
            'email' => 'admin@fitnessakhwat.test',
            'phone' => '081100000001',
            'password' => Hash::make('password'),
        ]);
        $memberUser = User::factory()->create([
            'name' => 'Aisyah Member',
            'email' => 'member@fitnessakhwat.test',
            'phone' => '081100000002',
            'password' => Hash::make('password'),
        ]);
        $trainerUser = User::factory()->create([
            'name' => 'Fatimah Trainer',
            'email' => 'trainer@fitnessakhwat.test',
            'phone' => '081100000003',
            'password' => Hash::make('password'),
        ]);

        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('super_admin');
            $memberUser->assignRole('Member');
            $trainerUser->assignRole('Trainer');
        }

        $member = Member::query()->create([
            'user_id' => $memberUser->id,
            'member_code' => 'MBR000001',
            'joined_at' => now()->subDays(10)->toDateString(),
        ]);
        $trainer = Trainer::query()->create([
            'user_id' => $trainerUser->id,
            'specialization' => 'Pilates & Strength',
            'bio' => 'Certified akhwat fitness trainer focused on safe, sustainable movement.',
            'is_active' => true,
        ]);

        $starter = MembershipPackage::query()->create([
            'name' => 'Starter Monthly',
            'description' => 'Access regular classes and member booking for 30 days.',
            'duration_days' => 30,
            'price' => 350000,
            'is_active' => true,
        ]);
        MembershipPackage::query()->create([
            'name' => 'Commitment Quarterly',
            'description' => 'Best for consistent weekly training over 90 days.',
            'duration_days' => 90,
            'price' => 900000,
            'is_active' => true,
        ]);
        MembershipPackage::query()->create([
            'name' => 'Akhwat Annual',
            'description' => 'Full-year access with priority booking.',
            'duration_days' => 365,
            'price' => 3200000,
            'is_active' => true,
        ]);

        MembershipPurchase::query()->create([
            'member_id' => $member->id,
            'membership_package_id' => $starter->id,
            'starts_at' => now()->subDays(2),
            'expires_at' => now()->addDays(28),
            'status' => 'active',
            'payment_method' => 'midtrans',
            'amount' => $starter->price,
            'payment_reference' => 'MID-SEED-MEMBER',
        ]);

        foreach (['Morning Pilates', 'Strength for Beginners', 'Yoga Flow'] as $index => $name) {
            FitnessClass::query()->create([
                'trainer_id' => $trainer->id,
                'name' => $name,
                'description' => 'Women-only class with guided technique and safe progression.',
                'capacity' => 12,
                'location' => 'Studio '.chr(65 + $index),
                'class_date' => now()->addDays($index + 1)->toDateString(),
                'start_time' => sprintf('%02d:00:00', 8 + $index),
                'end_time' => sprintf('%02d:00:00', 9 + $index),
                'is_active' => true,
            ]);
        }

        Attendance::query()->create([
            'member_id' => $member->id,
            'fitness_class_id' => FitnessClass::query()->first()->id,
            'check_in_time' => now()->subDay(),
            'status' => 'present',
            'location' => 'Fitness Akhwat Studio',
        ]);

        foreach ([
            ['Healthy Food', 'healthy-food'],
            ['Healthy Drink', 'healthy-drink'],
            ['Supplements', 'supplements'],
        ] as [$name, $slug]) {
            $category = ProductCategory::query()->create(['name' => $name, 'slug' => $slug]);
            Product::factory()->count(3)->create(['product_category_id' => $category->id]);
        }
    }
}
