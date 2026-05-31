<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('posplans')) {
            return;
        }

        Schema::create('posplans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('subtitle')->nullable();
            $table->double('price')->nullable()->default('NULL');
            $table->double('usd_price')->default(0);
            $table->string('discount_type')->nullable();
            $table->double('onedis')->nullable()->default('NULL');
            $table->decimal('threedis', 8, 2)->nullable()->default('NULL');
            $table->double('sixdis')->nullable()->default('NULL');
            $table->double('twelvedis')->nullable()->default('NULL');
            $table->double('twentyfourdis')->nullable()->default('NULL');
            $table->bigInteger('branch')->nullable()->default('NULL');
            $table->bigInteger('product')->nullable()->default('NULL');
            $table->bigInteger('staff')->nullable()->default('NULL');
            $table->bigInteger('order')->nullable()->default('NULL');
            $table->string('advance_report')->nullable()->default('No');
            $table->string('inventory')->nullable()->default('No');
            $table->string('pos_setup')->nullable()->default('No');
            $table->bigInteger('position')->nullable()->default(0);
            $table->decimal('payment_processing_charge', 8, 2)->default(0.0);
            $table->decimal('monthly_chat_support', 8, 2)->default(0.0);
            $table->string('status')->nullable()->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posplans');
    }
};