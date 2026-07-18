<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fitness_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained()->restrictOnDelete();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('capacity');
            $table->string('status')->default('scheduled');
            $table->timestamps();
            $table->unique(['fitness_class_id', 'session_date', 'start_time']);
        });

        Schema::table('class_bookings', function (Blueprint $table): void {
            $table->foreignId('class_session_id')->nullable()->after('fitness_class_id')->constrained()->nullOnDelete();
        });

        Schema::table('attendances', function (Blueprint $table): void {
            $table->foreignId('class_session_id')->nullable()->after('fitness_class_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('class_session_id');
        });

        Schema::table('class_bookings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('class_session_id');
        });

        Schema::dropIfExists('class_sessions');
    }
};
