<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\ScanMemberQr;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
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

    public function test_location_admin_can_access_member_qr_scanner(): void
    {
        $admin = $this->adminWithSeededRole('Admin di lokasi');

        $this->actingAs($admin)
            ->get('/admin/scan-member-qr')
            ->assertOk()
            ->assertSee('Scan QR Member');
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
            'attendance_type' => 'gym_visit',
            'status' => 'present',
            'location' => 'Akhwat Gym Studio',
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
