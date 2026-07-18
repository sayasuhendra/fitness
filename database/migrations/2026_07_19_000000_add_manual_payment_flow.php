<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->text('instructions')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('qris_payment_methods', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->default('QRIS Akhwat Gym');
            $table->string('image_path');
            $table->text('instructions')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_confirmations', function (Blueprint $table): void {
            $table->id();
            $table->morphs('payable');
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->string('proof_path')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->text('member_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_confirmations');
        Schema::dropIfExists('qris_payment_methods');
        Schema::dropIfExists('bank_accounts');
    }
};
