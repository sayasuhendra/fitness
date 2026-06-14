<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar_url')->nullable()->after('phone');
        });

        Schema::create('members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('member_code')->unique();
            $table->date('joined_at');
            $table->timestamps();
        });

        Schema::create('trainers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('specialization');
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('membership_packages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('duration_days');
            $table->decimal('price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('membership_purchases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_package_id')->constrained()->restrictOnDelete();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });

        Schema::create('fitness_classes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('capacity');
            $table->string('location');
            $table->date('class_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('class_bookings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fitness_class_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('confirmed');
            $table->dateTime('booked_at');
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
            $table->unique(['member_id', 'fitness_class_id']);
        });

        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fitness_class_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('check_in_time');
            $table->string('status')->default('present');
            $table->string('location')->default('Fitness Akhwat Studio');
            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_category_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock');
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->string('payment_method');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('device_tokens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token')->unique();
            $table->string('platform')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('class_bookings');
        Schema::dropIfExists('fitness_classes');
        Schema::dropIfExists('membership_purchases');
        Schema::dropIfExists('membership_packages');
        Schema::dropIfExists('trainers');
        Schema::dropIfExists('members');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['phone', 'avatar_url']);
        });
    }
};
