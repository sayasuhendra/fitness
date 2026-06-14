<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_packages', function (Blueprint $table): void {
            $table->string('package_type')->default('membership')->after('description');
            $table->string('billing_cycle')->default('monthly')->after('package_type');
            $table->boolean('includes_personal_trainer')->default(false)->after('billing_cycle');
            $table->boolean('has_visit_limit')->default(false)->after('includes_personal_trainer');
            $table->unsignedInteger('visit_limit')->nullable()->after('has_visit_limit');
            $table->unsignedInteger('discount_percent')->default(0)->after('price');
            $table->decimal('original_price', 12, 2)->nullable()->after('discount_percent');
        });

        Schema::table('membership_purchases', function (Blueprint $table): void {
            $table->boolean('includes_personal_trainer')->default(false)->after('status');
            $table->unsignedInteger('visits_allowed')->nullable()->after('includes_personal_trainer');
            $table->unsignedInteger('visits_used')->default(0)->after('visits_allowed');
        });

        Schema::table('fitness_classes', function (Blueprint $table): void {
            $table->string('class_type')->default('general')->after('name');
            $table->boolean('is_recurring')->default(false)->after('location');
            $table->json('recurring_days')->nullable()->after('is_recurring');
            $table->date('recurrence_ends_at')->nullable()->after('recurring_days');
            $table->boolean('allow_drop_in')->default(true)->after('is_active');
            $table->decimal('drop_in_price', 12, 2)->default(0)->after('allow_drop_in');
            $table->decimal('trainer_addon_price', 12, 2)->default(0)->after('drop_in_price');
        });

        Schema::table('class_bookings', function (Blueprint $table): void {
            $table->dropUnique(['member_id', 'fitness_class_id']);
            $table->date('booked_for_date')->nullable()->after('fitness_class_id');
            $table->string('access_type')->default('membership')->after('status');
            $table->boolean('personal_trainer_requested')->default(false)->after('access_type');
            $table->decimal('amount', 12, 2)->default(0)->after('personal_trainer_requested');
            $table->string('payment_method')->nullable()->after('amount');
            $table->string('payment_reference')->nullable()->after('payment_method');
        });

        DB::statement(<<<'SQL'
            UPDATE class_bookings
            SET booked_for_date = fitness_classes.class_date
            FROM fitness_classes
            WHERE class_bookings.fitness_class_id = fitness_classes.id
        SQL);

        Schema::table('class_bookings', function (Blueprint $table): void {
            $table->unique(['member_id', 'fitness_class_id', 'booked_for_date']);
        });
    }

    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table): void {
            $table->dropUnique(['member_id', 'fitness_class_id', 'booked_for_date']);
            $table->dropColumn([
                'booked_for_date',
                'access_type',
                'personal_trainer_requested',
                'amount',
                'payment_method',
                'payment_reference',
            ]);
            $table->unique(['member_id', 'fitness_class_id']);
        });

        Schema::table('fitness_classes', function (Blueprint $table): void {
            $table->dropColumn([
                'class_type',
                'is_recurring',
                'recurring_days',
                'recurrence_ends_at',
                'allow_drop_in',
                'drop_in_price',
                'trainer_addon_price',
            ]);
        });

        Schema::table('membership_purchases', function (Blueprint $table): void {
            $table->dropColumn([
                'includes_personal_trainer',
                'visits_allowed',
                'visits_used',
            ]);
        });

        Schema::table('membership_packages', function (Blueprint $table): void {
            $table->dropColumn([
                'package_type',
                'billing_cycle',
                'includes_personal_trainer',
                'has_visit_limit',
                'visit_limit',
                'discount_percent',
                'original_price',
            ]);
        });
    }
};
