<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FitnessApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_register_and_receive_expected_token_shape(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Aisyah',
            'email' => 'aisyah@example.test',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['user' => ['id', 'name', 'email', 'phone'], 'access_token', 'refresh_token']]);
    }

    public function test_member_can_purchase_membership_and_book_class(): void
    {
        $member = $this->actingMember();
        $package = MembershipPackage::factory()->create(['duration_days' => 30, 'price' => 350000]);
        $class = $this->fitnessClass();

        $this->postJson('/api/v1/memberships/purchase', [
            'package_id' => $package->id,
            'payment_method' => 'midtrans',
        ])->assertCreated()->assertJsonPath('data.status', 'active');

        $this->postJson('/api/v1/classes/book', ['class_id' => $class->id])
            ->assertCreated()
            ->assertJsonPath('data.status', 'confirmed');

        $this->assertDatabaseHas('class_bookings', [
            'member_id' => $member->id,
            'fitness_class_id' => $class->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_booking_requires_active_membership(): void
    {
        $this->actingMember();
        $class = $this->fitnessClass();

        $this->postJson('/api/v1/classes/book', ['class_id' => $class->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('membership');
    }

    public function test_checkout_reduces_stock(): void
    {
        $this->actingMember(withMembership: true);
        $category = ProductCategory::query()->create(['name' => 'Healthy Food', 'slug' => 'healthy-food']);
        $product = Product::factory()->create(['product_category_id' => $category->id, 'stock' => 5, 'price' => 25000]);

        $this->postJson('/api/v1/orders', [
            'payment_method' => 'midtrans',
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ])->assertCreated()->assertJsonPath('data.total_price', 50000);

        $this->assertSame(3, $product->fresh()->stock);
    }

    private function actingMember(bool $withMembership = false): Member
    {
        $user = User::factory()->create(['phone' => '08123456789']);
        $member = Member::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        if ($withMembership) {
            MembershipPurchase::factory()->create(['member_id' => $member->id]);
        }

        return $member;
    }

    private function fitnessClass(): FitnessClass
    {
        $trainer = Trainer::factory()->create();

        return FitnessClass::factory()->create(['trainer_id' => $trainer->id, 'capacity' => 2]);
    }
}
