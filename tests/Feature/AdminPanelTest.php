<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_admin_page_renders(): void
    {
        $admin = User::factory()->create();
        Role::findOrCreate('super_admin', 'web');
        $admin->assignRole('super_admin');

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
}
