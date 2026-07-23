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
            if (! Schema::hasColumn('users', 'admin_shift')) {
                $table->string('admin_shift')->nullable()->after('avatar_url');
            }
        });

        Schema::table('trainers', function (Blueprint $table): void {
            if (! Schema::hasColumn('trainers', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('specialization');
            }
        });

        Schema::table('products', function (Blueprint $table): void {
            if (! Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)->default(0)->after('price');
            }
        });

        Schema::table('order_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('order_items', 'unit_cost')) {
                $table->decimal('unit_cost', 12, 2)->default(0)->after('unit_price');
            }

            if (! Schema::hasColumn('order_items', 'subtotal_cost')) {
                $table->decimal('subtotal_cost', 12, 2)->default(0)->after('subtotal');
            }

            if (! Schema::hasColumn('order_items', 'profit_amount')) {
                $table->decimal('profit_amount', 12, 2)->default(0)->after('subtotal_cost');
            }
        });

        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'handled_by')) {
                $table->foreignId('handled_by')->nullable()->after('member_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('orders', 'handled_shift')) {
                $table->string('handled_shift')->nullable()->after('handled_by');
            }

            if (! Schema::hasColumn('orders', 'handled_date')) {
                $table->date('handled_date')->nullable()->after('handled_shift');
            }

            if (! Schema::hasColumn('orders', 'delivered_at')) {
                $table->dateTime('delivered_at')->nullable()->after('payment_reference');
            }

            if (! Schema::hasColumn('orders', 'delivered_by')) {
                $table->foreignId('delivered_by')->nullable()->after('delivered_at')->constrained('users')->nullOnDelete();
            }
        });

        Schema::table('membership_purchases', function (Blueprint $table): void {
            if (! Schema::hasColumn('membership_purchases', 'handled_by')) {
                $table->foreignId('handled_by')->nullable()->after('member_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('membership_purchases', 'handled_shift')) {
                $table->string('handled_shift')->nullable()->after('handled_by');
            }

            if (! Schema::hasColumn('membership_purchases', 'handled_date')) {
                $table->date('handled_date')->nullable()->after('handled_shift');
            }
        });

        Schema::table('payment_confirmations', function (Blueprint $table): void {
            if (! Schema::hasColumn('payment_confirmations', 'handled_shift')) {
                $table->string('handled_shift')->nullable()->after('verified_by');
            }

            if (! Schema::hasColumn('payment_confirmations', 'handled_date')) {
                $table->date('handled_date')->nullable()->after('handled_shift');
            }
        });

        Schema::table('attendances', function (Blueprint $table): void {
            if (! Schema::hasColumn('attendances', 'handled_by')) {
                $table->foreignId('handled_by')->nullable()->after('member_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('attendances', 'handled_shift')) {
                $table->string('handled_shift')->nullable()->after('handled_by');
            }

            if (! Schema::hasColumn('attendances', 'handled_date')) {
                $table->date('handled_date')->nullable()->after('handled_shift');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('handled_by');
            $table->dropColumn(['handled_shift', 'handled_date']);
        });

        Schema::table('payment_confirmations', function (Blueprint $table): void {
            $table->dropColumn(['handled_shift', 'handled_date']);
        });

        Schema::table('membership_purchases', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('handled_by');
            $table->dropColumn(['handled_shift', 'handled_date']);
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('handled_by');
            $table->dropConstrainedForeignId('delivered_by');
            $table->dropColumn(['handled_shift', 'handled_date', 'delivered_at']);
        });

        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropColumn(['unit_cost', 'subtotal_cost', 'profit_amount']);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn('cost_price');
        });

        Schema::table('trainers', function (Blueprint $table): void {
            $table->dropColumn('whatsapp_number');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('admin_shift');
        });
    }
};
