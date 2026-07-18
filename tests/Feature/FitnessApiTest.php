<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Payments\ApprovePaymentConfirmationAction;
use App\Models\ClassBooking;
use App\Models\DeviceToken;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\PaymentConfirmation;
use App\Models\PersonalTrainerSession;
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
            'payment_method' => 'bank_transfer',
        ])->assertCreated()->assertJsonPath('data.status', 'pending_payment');

        $purchase = MembershipPurchase::query()->where('member_id', $member->id)->firstOrFail();
        $confirmation = PaymentConfirmation::query()->create([
            'payable_type' => $purchase->getMorphClass(),
            'payable_id' => $purchase->id,
            'member_id' => $member->id,
            'payment_method' => 'bank_transfer',
            'amount' => $purchase->amount,
            'status' => 'pending',
        ]);

        app(ApprovePaymentConfirmationAction::class)->execute($confirmation, User::factory()->create());

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

    public function test_cancelling_booking_does_not_decrement_used_visit_quota(): void
    {
        $member = $this->actingMember();
        $membership = MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'visits_allowed' => 4,
            'visits_used' => 1,
        ]);
        $booking = ClassBooking::factory()->create([
            'member_id' => $member->id,
            'fitness_class_id' => $this->fitnessClass()->id,
            'access_type' => 'membership',
            'status' => 'confirmed',
        ]);

        $this->postJson('/api/v1/classes/cancel', ['booking_id' => $booking->id])
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');

        $this->assertSame(1, $membership->fresh()->visits_used);
    }

    public function test_class_listing_generates_date_sessions_and_booking_uses_session(): void
    {
        $member = $this->actingMember(withMembership: true);
        $class = $this->fitnessClass();
        $date = $class->class_date->toDateString();

        $response = $this->getJson("/api/v1/classes?date={$date}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $sessionId = $response->json('data.0.session_id');
        $this->assertNotNull($sessionId);

        $this->postJson('/api/v1/classes/book', [
            'class_id' => $class->id,
            'class_session_id' => $sessionId,
            'booked_for_date' => $date,
        ])->assertCreated()
            ->assertJsonPath('data.class_session_id', $sessionId);

        $this->assertDatabaseHas('class_bookings', [
            'member_id' => $member->id,
            'fitness_class_id' => $class->id,
            'class_session_id' => $sessionId,
            'status' => 'confirmed',
        ]);
    }

    public function test_checkout_reduces_stock_after_payment_approval(): void
    {
        $this->actingMember(withMembership: true);
        $category = ProductCategory::query()->create(['name' => 'Healthy Food', 'slug' => 'healthy-food']);
        $product = Product::factory()->create(['product_category_id' => $category->id, 'stock' => 5, 'price' => 25000]);

        $response = $this->postJson('/api/v1/orders', [
            'payment_method' => 'qris',
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ])->assertCreated()
            ->assertJsonPath('data.total_price', 50000)
            ->assertJsonPath('data.status', 'pending_payment');

        $this->assertSame(5, $product->fresh()->stock);

        $confirmation = PaymentConfirmation::query()->create([
            'payable_type' => Order::class,
            'payable_id' => $response->json('data.id'),
            'member_id' => Member::query()->firstOrFail()->id,
            'payment_method' => 'qris',
            'amount' => 50000,
            'status' => 'pending',
        ]);

        app(ApprovePaymentConfirmationAction::class)->execute($confirmation, User::factory()->create());

        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_member_can_create_personal_trainer_session_with_pt_membership(): void
    {
        $member = $this->actingMember();
        $package = MembershipPackage::factory()->create(['includes_personal_trainer' => true]);
        MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'includes_personal_trainer' => true,
        ]);
        $trainer = Trainer::factory()->create();

        $this->postJson('/api/v1/personal-trainer-sessions', [
            'trainer_id' => $trainer->id,
            'scheduled_at' => now()->addDay()->toISOString(),
            'access_type' => 'membership',
        ])->assertCreated()
            ->assertJsonPath('data.status', 'scheduled')
            ->assertJsonPath('data.access_type', 'membership');

        $this->assertDatabaseHas('personal_trainer_sessions', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_one_time_personal_trainer_session_waits_for_payment_approval(): void
    {
        $member = $this->actingMember();
        $trainer = Trainer::factory()->create();

        $response = $this->postJson('/api/v1/personal-trainer-sessions', [
            'trainer_id' => $trainer->id,
            'scheduled_at' => now()->addDay()->toISOString(),
            'access_type' => 'one_time',
            'payment_method' => 'qris',
        ])->assertCreated()
            ->assertJsonPath('data.status', 'pending_payment')
            ->assertJsonPath('data.amount', 80000)
            ->assertJsonPath('data.access_type', 'one_time');

        $session = PersonalTrainerSession::query()->findOrFail($response->json('data.id'));
        $confirmation = PaymentConfirmation::query()->create([
            'payable_type' => $session->getMorphClass(),
            'payable_id' => $session->id,
            'member_id' => $member->id,
            'payment_method' => 'qris',
            'amount' => $session->amount,
            'status' => 'pending',
        ]);

        app(ApprovePaymentConfirmationAction::class)->execute($confirmation, User::factory()->create());

        $this->assertSame('scheduled', $session->fresh()->status);
    }

    public function test_member_can_list_active_trainers(): void
    {
        $this->actingMember();
        $trainer = Trainer::factory()->create(['is_active' => true]);

        $this->getJson('/api/v1/trainers')
            ->assertOk()
            ->assertJsonPath('data.0.id', $trainer->id)
            ->assertJsonPath('data.0.name', $trainer->user->name);
    }

    public function test_member_can_register_fcm_token(): void
    {
        $member = $this->actingMember();

        $this->postJson('/api/v1/notifications/fcm-token', [
            'fcm_token' => 'demo-fcm-token',
            'platform' => 'android',
        ])->assertOk()
            ->assertJsonPath('data.platform', 'android');

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $member->user_id,
            'token' => 'demo-fcm-token',
            'platform' => 'android',
        ]);
    }

    public function test_payment_approval_creates_member_notification(): void
    {
        $member = $this->actingMember();
        DeviceToken::query()->create([
            'user_id' => $member->user_id,
            'token' => 'demo-fcm-token',
            'platform' => 'android',
        ]);
        $purchase = MembershipPurchase::factory()->create([
            'member_id' => $member->id,
            'status' => 'pending_payment',
        ]);
        $confirmation = PaymentConfirmation::query()->create([
            'payable_type' => $purchase->getMorphClass(),
            'payable_id' => $purchase->id,
            'member_id' => $member->id,
            'payment_method' => 'qris',
            'amount' => $purchase->amount,
            'status' => 'pending',
        ]);

        app(ApprovePaymentConfirmationAction::class)->execute($confirmation, User::factory()->create());

        $this->getJson('/api/v1/notifications')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Paket membership aktif')
            ->assertJsonPath('data.0.type', 'payment_approved');
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
