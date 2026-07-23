<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\LocationOperations;
use App\Filament\Pages\ScanMemberQr;
use App\Filament\Pages\SendMemberNotification;
use App\Filament\Pages\ShiftRevenueReport;
use App\Filament\Resources\Members\Pages\CreateMember;
use App\Filament\Resources\Trainers\Pages\CreateTrainer;
use App\Filament\Widgets\BookingAttendanceChart;
use App\Filament\Widgets\RevenueTrendChart;
use App\Filament\Widgets\ShiftRevenueBreakdownChart;
use App\Models\Attendance;
use App\Models\ClassBooking;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Trainer;
use App\Models\User;
use App\Support\AdminShift;
use Database\Seeders\AdminRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_admin_page_renders(): void
    {
        $admin = $this->superAdmin();

        $member = Member::factory()->create();
        $package = MembershipPackage::factory()->create();

        MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
        ]);

        $this->actingAs($admin)
            ->get('/admin/members')
            ->assertOk()
            ->assertSee('Members')
            ->assertSee('Daftarkan Member');
    }

    public function test_members_create_page_renders(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)
            ->get('/admin/members/create')
            ->assertOk()
            ->assertSee('Cara menghubungkan akun')
            ->assertSee('Buat akun baru')
            ->assertDontSee('Kode Member');

        Livewire::test(CreateMember::class)
            ->assertFormSet([
                'user_mode' => 'new',
            ]);
    }

    public function test_admin_can_create_member_from_existing_user(): void
    {
        $admin = $this->adminWithSeededRole('Super admin');
        $user = User::factory()->create([
            'name' => 'Member Existing',
            'email' => 'member.existing@example.test',
        ]);

        $this->actingAs($admin);

        Livewire::test(CreateMember::class)
            ->fillForm([
                'user_mode' => 'existing',
                'user_id' => $user->id,
                'joined_at' => today()->toDateString(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertTrue($user->fresh()->hasRole('Member'));
        $this->assertDatabaseHas('members', [
            'user_id' => $user->id,
            'member_code' => 'MBR000001',
        ]);
    }

    public function test_admin_can_create_member_with_new_user(): void
    {
        $admin = $this->adminWithSeededRole('Super admin');

        $this->actingAs($admin);

        Livewire::test(CreateMember::class)
            ->fillForm([
                'user_mode' => 'new',
                'new_user_name' => 'Member Baru',
                'new_user_email' => 'member.baru@example.test',
                'new_user_phone' => '6281122334455',
                'new_user_password' => 'password123',
                'joined_at' => today()->toDateString(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::query()->where('email', 'member.baru@example.test')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertTrue($user->hasRole('Member'));
        $this->assertDatabaseHas('members', [
            'user_id' => $user->id,
            'member_code' => 'MBR000001',
        ]);
        $this->assertSame(1, Member::query()->where('user_id', $user->id)->count());
    }

    public function test_users_admin_page_renders(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertOk()
            ->assertSee('Users');
    }

    public function test_admin_can_create_trainer_from_existing_user(): void
    {
        $admin = $this->adminWithSeededRole('Super admin');
        $user = User::factory()->create([
            'name' => 'Coach Existing',
            'email' => 'coach.existing@example.test',
        ]);

        $this->actingAs($admin);

        Livewire::test(CreateTrainer::class)
            ->assertFormSet([
                'user_mode' => 'new',
            ]);

        Livewire::test(CreateTrainer::class)
            ->fillForm([
                'user_mode' => 'existing',
                'user_id' => $user->id,
                'specialization' => 'Yoga',
                'whatsapp_number' => '6281234567890',
                'bio' => 'Coach yoga untuk kelas reguler.',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('trainers', [
            'user_id' => $user->id,
            'specialization' => 'Yoga',
            'whatsapp_number' => '6281234567890',
        ]);
    }

    public function test_admin_can_create_trainer_with_new_user(): void
    {
        $admin = $this->adminWithSeededRole('Super admin');

        $this->actingAs($admin);

        Livewire::test(CreateTrainer::class)
            ->fillForm([
                'user_mode' => 'new',
                'new_user_name' => 'Coach Baru',
                'new_user_email' => 'coach.baru@example.test',
                'new_user_phone' => '6289876543210',
                'new_user_password' => 'password123',
                'specialization' => 'Zumba',
                'whatsapp_number' => '6289876543210',
                'bio' => 'Coach zumba untuk kelas komunitas.',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::query()->where('email', 'coach.baru@example.test')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertTrue($user->hasRole('Trainer'));
        $this->assertDatabaseHas('trainers', [
            'user_id' => $user->id,
            'specialization' => 'Zumba',
            'whatsapp_number' => '6289876543210',
        ]);
        $this->assertSame(1, Trainer::query()->where('user_id', $user->id)->count());
    }

    public function test_roles_admin_page_renders(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)
            ->get('/admin/access/roles')
            ->assertOk()
            ->assertSee('Roles');
    }

    public function test_owner_can_manage_payment_setup_pages(): void
    {
        $owner = $this->adminWithSeededRole('Owner');

        $this->actingAs($owner)
            ->get('/admin/bank-accounts')
            ->assertOk();

        $this->actingAs($owner)
            ->get('/admin/qris-payment-methods')
            ->assertOk();
    }

    public function test_location_admin_can_verify_payments_but_cannot_manage_payment_setup_or_users(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');

        $this->actingAs($admin)
            ->get('/admin/payment-confirmations')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/bank-accounts')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('/admin/qris-payment-methods')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertForbidden();
    }

    public function test_admin_can_open_store_and_transaction_management_pages(): void
    {
        $admin = $this->adminWithSeededRole('Super admin');

        foreach ([
            '/admin/products',
            '/admin/orders',
            '/admin/membership-purchases',
            '/admin/payment-confirmations',
            '/admin/attendances',
        ] as $path) {
            $this->actingAs($admin)
                ->get($path)
                ->assertOk();
        }
    }

    public function test_location_admin_can_access_member_qr_scanner(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');

        $this->actingAs($admin)
            ->get('/admin/scan-member-qr')
            ->assertOk()
            ->assertSee('Scan QR Member');
    }

    public function test_location_admin_can_access_location_operations_page(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');

        $this->actingAs($admin)
            ->get('/admin/location-operations')
            ->assertOk()
            ->assertSee('Operasional Lokasi');
    }

    public function test_owner_can_access_shift_revenue_report_but_location_admin_cannot(): void
    {
        $owner = $this->adminWithSeededRole('Owner');
        $locationAdmin = $this->adminWithSeededRole('Admin di lokasi');

        $this->actingAs($owner)
            ->get('/admin/shift-revenue-report')
            ->assertOk()
            ->assertSee('Laporan Pendapatan Shift');

        $this->actingAs($locationAdmin)
            ->get('/admin/shift-revenue-report')
            ->assertForbidden();
    }

    public function test_shift_revenue_report_includes_transactions_on_end_date(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');
        $admin->update(['admin_shift' => AdminShift::SHIFT_1]);
        $member = Member::factory()->create();
        $package = MembershipPackage::factory()->create(['price' => 215000]);
        $product = Product::factory()->create([
            'price' => 35000,
            'cost_price' => 22000,
        ]);

        MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'handled_by' => $admin->id,
            'handled_shift' => AdminShift::SHIFT_1,
            'handled_date' => today()->toDateString(),
            'amount' => 215000,
            'status' => 'active',
        ]);

        $order = Order::factory()->create([
            'member_id' => $member->id,
            'handled_by' => $admin->id,
            'handled_shift' => AdminShift::SHIFT_1,
            'handled_date' => today()->toDateString(),
            'status' => 'completed',
            'total_price' => 70000,
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 35000,
            'unit_cost' => 22000,
            'subtotal' => 70000,
            'subtotal_cost' => 44000,
            'profit_amount' => 26000,
        ]);

        $page = app(ShiftRevenueReport::class);
        $page->mount();

        $row = $page->rows()->firstWhere('date', today()->toDateString());

        $this->assertNotNull($row);
        $this->assertSame(215000.0, $row['membership_revenue']);
        $this->assertSame(70000.0, $row['store_revenue']);
        $this->assertSame(26000.0, $row['store_profit']);
        $this->assertSame(285000.0, $row['total_revenue']);
    }

    public function test_revenue_dashboard_charts_support_shift_filters(): void
    {
        $shift1Admin = $this->adminWithSeededRole('Admin di lokasi');
        $shift1Admin->update(['admin_shift' => AdminShift::SHIFT_1]);
        $shift2Admin = $this->adminWithSeededRole('Admin di lokasi');
        $shift2Admin->update(['admin_shift' => AdminShift::SHIFT_2]);
        $member = Member::factory()->create();
        $package = MembershipPackage::factory()->create(['price' => 100000]);
        $product = Product::factory()->create([
            'price' => 40000,
            'cost_price' => 25000,
        ]);

        MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'handled_by' => $shift1Admin->id,
            'handled_shift' => AdminShift::SHIFT_1,
            'handled_date' => today()->toDateString(),
            'amount' => 100000,
            'status' => 'active',
        ]);

        MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'handled_by' => $shift2Admin->id,
            'handled_shift' => AdminShift::SHIFT_2,
            'handled_date' => today()->toDateString(),
            'amount' => 200000,
            'status' => 'active',
        ]);

        $order = Order::factory()->create([
            'member_id' => $member->id,
            'handled_by' => $shift2Admin->id,
            'handled_shift' => AdminShift::SHIFT_2,
            'handled_date' => today()->toDateString(),
            'status' => 'completed',
            'total_price' => 80000,
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 40000,
            'unit_cost' => 25000,
            'subtotal' => 80000,
            'subtotal_cost' => 50000,
            'profit_amount' => 30000,
        ]);

        $trend = app(RevenueTrendChart::class);
        $trend->filters = [
            'shift' => AdminShift::SHIFT_2,
            'date_from' => today()->toDateString(),
            'date_to' => today()->toDateString(),
        ];
        $trendData = $this->chartData($trend);

        $this->assertSame([280000.0], $trendData['datasets'][0]['data']);
        $this->assertSame([200000.0], $trendData['datasets'][1]['data']);
        $this->assertSame([80000.0], $trendData['datasets'][2]['data']);
        $this->assertSame([30000.0], $trendData['datasets'][3]['data']);

        $breakdown = app(ShiftRevenueBreakdownChart::class);
        $breakdown->filters = [
            'date_from' => today()->toDateString(),
            'date_to' => today()->toDateString(),
        ];
        $breakdownData = $this->chartData($breakdown);

        $this->assertSame([100000.0, 200000.0], $breakdownData['datasets'][0]['data']);
        $this->assertSame([0.0, 80000.0], $breakdownData['datasets'][1]['data']);
        $this->assertSame([0.0, 30000.0], $breakdownData['datasets'][2]['data']);
    }

    public function test_booking_attendance_chart_supports_date_filters(): void
    {
        $member = Member::factory()->create();
        $class = FitnessClass::factory()->create();

        ClassBooking::factory()->create([
            'member_id' => $member->id,
            'fitness_class_id' => $class->id,
            'status' => 'confirmed',
            'booked_for_date' => today()->toDateString(),
            'booked_at' => today()->setTime(9, 0),
        ]);
        ClassBooking::factory()->create([
            'member_id' => $member->id,
            'fitness_class_id' => $class->id,
            'status' => 'confirmed',
            'booked_for_date' => today()->subDays(3)->toDateString(),
            'booked_at' => today()->subDays(3)->setTime(9, 0),
        ]);

        Attendance::query()->create([
            'member_id' => $member->id,
            'fitness_class_id' => $class->id,
            'check_in_time' => today()->setTime(10, 0),
            'status' => 'present',
            'location' => 'Akhwat Gym Studio',
        ]);
        Attendance::query()->create([
            'member_id' => $member->id,
            'fitness_class_id' => $class->id,
            'check_in_time' => today()->subDays(3)->setTime(10, 0),
            'status' => 'present',
            'location' => 'Akhwat Gym Studio',
        ]);

        $chart = app(BookingAttendanceChart::class);
        $chart->filters = [
            'date_from' => today()->toDateString(),
            'date_to' => today()->toDateString(),
        ];
        $data = $this->chartData($chart);

        $this->assertSame([1], $data['datasets'][0]['data']);
        $this->assertSame([1], $data['datasets'][1]['data']);
        $this->assertSame([today()->format('d M')], $data['labels']);
    }

    public function test_member_qr_scanner_records_gym_visit_attendance(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');
        $member = Member::factory()->create();
        $package = MembershipPackage::factory()->create();

        MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addMonth(),
            'visits_allowed' => 8,
            'visits_used' => 0,
        ]);

        $payload = Crypt::encryptString(json_encode([
            'member_id' => $member->id,
            'expires_at' => now()->addMinutes(10)->toISOString(),
        ], JSON_THROW_ON_ERROR));

        $this->actingAs($admin);

        Livewire::test(ScanMemberQr::class)
            ->set('location', 'Akhwat Gym Studio')
            ->call('submitScan', $payload)
            ->assertSet('lastCheckIn.member_code', $member->member_code);

        $this->assertDatabaseHas('attendances', [
            'member_id' => $member->id,
            'handled_by' => $admin->id,
            'handled_shift' => AdminShift::forUser($admin),
            'attendance_type' => 'gym_visit',
            'status' => 'present',
            'location' => 'Akhwat Gym Studio',
        ]);
    }

    public function test_location_operations_product_order_tags_admin_shift_and_profit(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');
        $admin->update(['admin_shift' => AdminShift::SHIFT_1]);
        $member = Member::factory()->create();
        $drink = Product::factory()->create([
            'price' => 30000,
            'cost_price' => 18000,
            'stock' => 5,
            'is_active' => true,
        ]);
        $snack = Product::factory()->create([
            'price' => 20000,
            'cost_price' => 12000,
            'stock' => 4,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(LocationOperations::class)
            ->set('orderMemberId', $member->id)
            ->set('productId', $drink->id)
            ->set('productQuantity', 2)
            ->call('addProductToOrder')
            ->set('productId', $snack->id)
            ->set('productQuantity', 1)
            ->call('addProductToOrder')
            ->set('orderPaymentMethod', 'cash')
            ->call('sellProduct');

        $order = Order::query()->with('items')->firstOrFail();

        $this->assertSame('paid', $order->status);
        $this->assertSame($admin->id, $order->handled_by);
        $this->assertSame(AdminShift::SHIFT_1, $order->handled_shift);
        $this->assertSame(2, $order->items->count());
        $this->assertSame(3, $drink->fresh()->stock);
        $this->assertSame(3, $snack->fresh()->stock);
        $this->assertEquals(32000.0, $order->items->sum('profit_amount'));
    }

    public function test_location_admin_can_access_send_member_notification_page(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');

        $this->actingAs($admin)
            ->get('/admin/send-member-notification')
            ->assertOk()
            ->assertSee('Kirim Notifikasi')
            ->assertSee('Saat Notifikasi Dibuka, Arahkan Member ke')
            ->assertSee('Riwayat Belanja');
    }

    public function test_admin_can_send_notification_to_selected_member(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');
        $member = Member::factory()->create();
        $otherMember = Member::factory()->create();

        $this->actingAs($admin);

        Livewire::test(SendMemberNotification::class)
            ->set('target', 'selected')
            ->set('memberIds', [$member->id])
            ->set('notificationTitle', 'Tes FCM')
            ->set('body', 'Pesan untuk member tertentu.')
            ->set('type', 'admin_broadcast')
            ->set('actionUrl', '/notifications')
            ->call('send')
            ->assertSet('sentCount', 1);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => $member->user->getMorphClass(),
            'notifiable_id' => $member->user_id,
            'type' => 'App\\Notifications\\MemberEventNotification',
        ]);

        $this->assertDatabaseMissing('notifications', [
            'notifiable_type' => $otherMember->user->getMorphClass(),
            'notifiable_id' => $otherMember->user_id,
            'type' => 'App\\Notifications\\MemberEventNotification',
        ]);
    }

    private function superAdmin(): User
    {
        $admin = User::factory()->create();
        Role::findOrCreate('Super admin', 'web');
        $admin->assignRole('Super admin');

        return $admin;
    }

    private function adminWithSeededRole(string $role): User
    {
        $this->seed(AdminRoleSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole($role);

        return $admin;
    }

    private function chartData(object $chart): array
    {
        $method = new \ReflectionMethod($chart, 'getData');
        $method->setAccessible(true);

        return $method->invoke($chart);
    }
}
