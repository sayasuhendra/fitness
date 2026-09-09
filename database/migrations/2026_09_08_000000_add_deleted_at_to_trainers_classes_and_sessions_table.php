<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table): void {
            if (! Schema::hasColumn('trainers', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('fitness_classes', function (Blueprint $table): void {
            if (! Schema::hasColumn('fitness_classes', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('class_sessions', function (Blueprint $table): void {
            if (! Schema::hasColumn('class_sessions', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('class_sessions', function (Blueprint $table): void {
            if (Schema::hasColumn('class_sessions', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('fitness_classes', function (Blueprint $table): void {
            if (Schema::hasColumn('fitness_classes', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('trainers', function (Blueprint $table): void {
            if (Schema::hasColumn('trainers', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
