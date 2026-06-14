<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassBooking;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Trainer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'description' => 'Membership bulanan tanpa personal trainer, cocok untuk member yang ingin latihan mandiri.',
            'package_type' => 'membership',
            'billing_cycle' => 'monthly',
            'includes_personal_trainer' => false,
            'has_visit_limit' => true,
            'visit_limit' => 12,
            'duration_days' => 30,
            'price' => 350000,
            'discount_percent' => 0,
            'original_price' => null,
            'is_active' => true,
        ]);
        MembershipPackage::query()->updateOrCreate([
            'name' => 'Personal Trainer Bulanan',
        ], [
            'description' => 'Membership bulanan dengan personal trainer untuk pendampingan latihan yang lebih terarah.',
            'package_type' => 'membership',
            'billing_cycle' => 'monthly',
            'includes_personal_trainer' => true,
            'has_visit_limit' => true,
            'visit_limit' => 8,
            'duration_days' => 30,
            'price' => 950000,
            'discount_percent' => 0,
            'original_price' => null,
            'is_active' => true,
        ]);
        MembershipPackage::query()->updateOrCreate([
            'name' => 'Akhwat Tahunan',
        ], [
            'description' => 'Membership tahunan unlimited visit dengan harga lebih hemat untuk rutinitas jangka panjang.',
            'package_type' => 'membership',
            'billing_cycle' => 'yearly',
            'includes_personal_trainer' => false,
            'has_visit_limit' => false,
            'visit_limit' => null,
            'duration_days' => 365,
            'price' => 3200000,
            'discount_percent' => 15,
            'original_price' => 3800000,
            'is_active' => true,
        ]);
        MembershipPackage::query()->updateOrCreate([
            'name' => 'One-Time Visit',
        ], [
            'description' => 'Sekali datang tanpa personal trainer untuk calon member yang ingin mencoba kelas.',
            'package_type' => 'one_time',
            'billing_cycle' => 'one_time',
            'includes_personal_trainer' => false,
            'has_visit_limit' => true,
            'visit_limit' => 1,
            'duration_days' => 1,
            'price' => 75000,
            'discount_percent' => 0,
            'original_price' => null,
            'is_active' => true,
        ]);
        MembershipPackage::query()->updateOrCreate([
            'name' => 'One-Time Visit + Personal Trainer',
        ], [
            'description' => 'Sekali datang dengan personal trainer untuk pengalaman latihan yang lebih personal.',
            'package_type' => 'one_time',
            'billing_cycle' => 'one_time',
            'includes_personal_trainer' => true,
            'has_visit_limit' => true,
            'visit_limit' => 1,
            'duration_days' => 1,
            'price' => 125000,
            'discount_percent' => 0,
            'original_price' => null,
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
            'includes_personal_trainer' => $starter->includes_personal_trainer,
            'visits_allowed' => $starter->visit_limit,
            'visits_used' => 0,
            'payment_method' => 'midtrans',
            'amount' => $starter->price,
            'payment_reference' => 'MID-SEED-MEMBER',
        ]);

        $classSeeds = [
            [
                'name' => 'Pilates Pagi',
                'date' => now()->addDays(1)->toDateString(),
                'description' => 'Kelas pilates untuk postur, core strength, dan mobilitas ringan.',
                'class_type' => 'pilates',
                'location' => 'Studio A',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'is_recurring' => true,
                'recurring_days' => ['monday', 'wednesday', 'friday'],
            ],
            [
                'name' => 'Strength untuk Pemula',
                'date' => now()->addDays(2)->toDateString(),
                'description' => 'Latihan beban dasar dengan teknik aman dan pendampingan trainer.',
                'class_type' => 'strength',
                'location' => 'Studio B',
                'start_time' => '09:30:00',
                'end_time' => '10:30:00',
                'is_recurring' => true,
                'recurring_days' => ['tuesday', 'thursday'],
            ],
            [
                'name' => 'Yoga Flow',
                'date' => now()->addDays(3)->toDateString(),
                'description' => 'Sesi flow santai untuk fleksibilitas, napas, dan pemulihan tubuh.',
                'class_type' => 'yoga',
                'location' => 'Studio C',
                'start_time' => '16:00:00',
                'end_time' => '17:00:00',
                'is_recurring' => true,
                'recurring_days' => ['saturday', 'sunday'],
            ],
            [
                'name' => 'Zumba Akhwat',
                'date' => now()->addDays(1)->toDateString(),
                'description' => 'Kelas cardio dance yang fun untuk stamina dan mood booster.',
                'class_type' => 'zumba',
                'location' => 'Studio B',
                'start_time' => '18:30:00',
                'end_time' => '19:30:00',
                'is_recurring' => true,
                'recurring_days' => ['monday', 'thursday'],
            ],
            [
                'name' => 'Circuit Training',
                'date' => now()->addDays(2)->toDateString(),
                'description' => 'Latihan circuit intensitas sedang dengan beberapa station gerakan.',
                'class_type' => 'circuit_training',
                'location' => 'Studio A',
                'start_time' => '17:00:00',
                'end_time' => '18:00:00',
                'is_recurring' => true,
                'recurring_days' => ['tuesday', 'friday'],
            ],
            [
                'name' => 'Mobility Recovery',
                'date' => now()->subDay()->toDateString(),
                'description' => 'Kelas pemulihan setelah latihan untuk sendi dan otot.',
                'class_type' => 'general',
                'location' => 'Studio A',
                'start_time' => '07:00:00',
                'end_time' => '08:00:00',
                'is_recurring' => false,
                'recurring_days' => null,
            ],
        ];

        $classes = new Collection;
        foreach ($classSeeds as $seed) {
            $classes->push(FitnessClass::query()->updateOrCreate([
                'name' => $seed['name'],
                'class_date' => $seed['date'],
            ], [
                'trainer_id' => $trainer->id,
                'description' => $seed['description'],
                'class_type' => $seed['class_type'],
                'capacity' => 12,
                'location' => $seed['location'],
                'is_recurring' => $seed['is_recurring'],
                'recurring_days' => $seed['recurring_days'],
                'recurrence_ends_at' => now()->addMonths(3)->toDateString(),
                'start_time' => $seed['start_time'],
                'end_time' => $seed['end_time'],
                'is_active' => true,
                'allow_drop_in' => true,
                'drop_in_price' => 75000,
                'trainer_addon_price' => 50000,
            ]));
        }

        $upcomingClass = $classes->firstWhere('name', 'Pilates Pagi');
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
