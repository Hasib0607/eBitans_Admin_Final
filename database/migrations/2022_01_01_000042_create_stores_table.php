<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stores')) {
            return;
        }

        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('url')->nullable();
            $table->string('type')->nullable();
            $table->string('category_id', 191)->nullable();
            $table->string('purpose')->nullable();
            $table->string('user_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('status')->nullable();
            $table->tinyInteger('store_status')->default(1);
            $table->tinyInteger('paid_registration')->default(0);
            $table->string('plan_id')->nullable();
            $table->string('template_id')->nullable();
            $table->integer('currency')->default(1);
            $table->decimal('currency_rate', 10, 4)->default(117.56);
            $table->string('bkash')->nullable()->default('no');
            $table->date('purchase_date')->nullable();
            $table->date('renew_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedInteger('dropship_commission')->default(3);
            $table->tinyInteger('order_pull')->default(0);
            $table->decimal('overflow_commission', 8, 2)->default(10000.0);
            $table->string('pos_plan_id')->nullable();
            $table->date('pos_plan_start_date')->nullable();
            $table->date('pos_plan_expiry_date')->nullable();
            $table->bigInteger('pos_plan_month')->nullable()->default(0);
            $table->string('pos_plan_status')->nullable();
            $table->string('digital_plan_id')->nullable();
            $table->date('digital_plan_start_date')->nullable();
            $table->date('digital_plan_end_date')->nullable();
            $table->string('digital_plan_status')->nullable();
            $table->string('plan_status')->nullable();
            $table->integer('trail')->default(0);
            $table->bigInteger('month')->nullable()->default('NULL');
            $table->string('upcoming_plan_id')->nullable();
            $table->string('upcoming_plan_month')->nullable();
            $table->timestamp('upcoming_plan_purchase_date')->nullable();
            $table->timestamp('upcoming_plan_expiry_date')->nullable();
            $table->string('upcoming_pos_plan_id')->nullable();
            $table->string('upcoming_pos_plan_month')->nullable();
            $table->date('upcoming_pos_plan_start_date')->nullable();
            $table->date('upcoming_pos_plan_expiry_date')->nullable();
            $table->string('upcoming_digital_plan_id')->nullable();
            $table->string('upcoming_digital_plan_month')->nullable();
            $table->date('upcoming_digital_plan_start_date')->nullable();
            $table->date('upcoming_digital_plan_expiry_date')->nullable();
            $table->string('webmail_status')->nullable();
            $table->integer('sms_plan')->nullable()->default(0);
            $table->string('auth_type')->default('EmailEasyOrder');
            $table->integer('pay_noti')->default(1);
            $table->tinyInteger('call_status')->default(0);
            $table->string('sms_status', 191)->default('0');
            $table->tinyInteger('pay_mail_status')->default(0);
            $table->tinyInteger('setup_status')->default(0);
            $table->tinyInteger('isDomainDelete')->default(0);
            $table->tinyInteger('isCFileDelete')->default(0);
            $table->string('analytic_email', 191)->nullable();
            $table->longText('bkash_token');
            $table->string('alert_popup', 191)->nullable();
            $table->bigInteger('access_key')->nullable()->default('NULL');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique('access_key', 'access_key');
            $table->index('expiry_date', 'idx_expiry');
            $table->index(['url', 'expiry_date'], 'idx_stores_url_expiry');
            $table->index('name', 'stores_name_index');
            $table->index('url', 'stores_url_index');
            $table->index('user_id', 'stores_user_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};