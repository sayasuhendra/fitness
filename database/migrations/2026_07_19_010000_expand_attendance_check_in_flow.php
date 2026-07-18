<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->foreignId('class_booking_id')->nullable()->after('fitness_class_id')->constrained()->nullOnDelete();
            $table->foreignId('membership_purchase_id')->nullable()->after('class_booking_id')->constrained()->nullOnDelete();
            $table->string('attendance_type')->default('gym_visit')->after('membership_purchase_id');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('class_booking_id');
            $table->dropConstrainedForeignId('membership_purchase_id');
            $table->dropColumn('attendance_type');
        });
    }
};
