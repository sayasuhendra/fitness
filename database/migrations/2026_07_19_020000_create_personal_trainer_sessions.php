<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_trainer_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained()->restrictOnDelete();
            $table->foreignId('membership_purchase_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('scheduled_at');
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->string('status')->default('scheduled');
            $table->string('access_type')->default('membership');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('member_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::table('attendances', function (Blueprint $table): void {
            $table->foreignId('personal_trainer_session_id')
                ->nullable()
                ->after('class_booking_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('personal_trainer_session_id');
        });

        Schema::dropIfExists('personal_trainer_sessions');
    }
};
