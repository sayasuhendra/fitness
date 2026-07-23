<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\ScanMemberQr;
use App\Filament\Pages\SendMemberNotification;
use App\Filament\Pages\LocationOperations;
use App\Models\Order;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Product;
use App\Support\AdminShift;
use App\Models\User;
use Database\Seeders\AdminRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
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
            ->assertSee('Members');
    }

    public function test_users_admin_page_renders(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertOk()
            ->assertSee('Users');
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
        $product = Product::factory()->create([
            'price' => 30000,
            'cost_price' => 18000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(LocationOperations::class)
            ->set('orderMemberId', $member->id)
            ->set('productId', $product->id)
            ->set('productQuantity', 2)
            ->set('orderPaymentMethod', 'cash')
            ->call('sellProduct');

        $order = Order::query()->with('items')->firstOrFail();

        $this->assertSame('paid', $order->status);
        $this->assertSame($admin->id, $order->handled_by);
        $this->assertSame(AdminShift::SHIFT_1, $order->handled_shift);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertSame('24000.00', (string) $order->items->first()->profit_amount);
    }

    public function test_location_admin_can_access_send_member_notification_page(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');

        $this->actingAs($admin)
            ->get('/admin/send-member-notification')
            ->assertOk()
            ->assertSee('Kirim Notifikasi');
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
}
