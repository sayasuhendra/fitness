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
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE personal_trainer_sessions ALTER COLUMN trainer_id DROP NOT NULL');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE personal_trainer_sessions MODIFY trainer_id BIGINT UNSIGNED NULL');
        }

        Schema::create('personal_trainers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('specialization');
            $table->string('whatsapp_number')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('personal_trainer_sessions', function (Blueprint $table): void {
            $table->unsignedBigInteger('personal_trainer_id')
                ->nullable()
                ->after('trainer_id');
        });

        if ($driver !== 'sqlite') {
            Schema::table('personal_trainer_sessions', function (Blueprint $table): void {
                $table->foreign('personal_trainer_id')
                    ->references('id')
                    ->on('personal_trainers')
                    ->nullOnDelete();
            });
        }

        Schema::create('app_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'membership_expiry_reminder_days'],
            ['value' => json_encode([7, 3, 1], JSON_THROW_ON_ERROR), 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');

        Schema::table('personal_trainer_sessions', function (Blueprint $table): void {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['personal_trainer_id']);
            }

            $table->dropColumn('personal_trainer_id');
        });

        Schema::dropIfExists('personal_trainers');
    }
};
