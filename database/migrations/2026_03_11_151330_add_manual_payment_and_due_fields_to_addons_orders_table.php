<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addons_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('addons_orders', 'manual_discount')) {
                $table->decimal('manual_discount', 10, 2)->default(0)->after('late_fee_overdue_days');
            }
            if (! Schema::hasColumn('addons_orders', 'manual_discount_comment')) {
                $table->text('manual_discount_comment')->nullable()->after('manual_discount');
            }

            if (! Schema::hasColumn('addons_orders', 'payment_type')) {
                $table->string('payment_type')->default('full')->after('payment_method');
            }

            if (! Schema::hasColumn('addons_orders', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->nullable()->after('payment_type');
            }
            if (! Schema::hasColumn('addons_orders', 'due_amount')) {
                $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount');
            }
            if (! Schema::hasColumn('addons_orders', 'due_amount_status')) {
                $table->string('due_amount_status')->default('paid')->after('due_amount');
            }

            if (! Schema::hasColumn('addons_orders', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('due_amount_status');
            }
            if (! Schema::hasColumn('addons_orders', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('addons_orders', function (Blueprint $table) {
            $table->dropColumn([
                'manual_discount',
                'manual_discount_comment',
                'payment_type',
                'paid_amount',
                'due_amount',
                'due_amount_status',
                'bank_name',
                'account_number',
            ]);
        });
    }
};