<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('addons')) {
            return;
        }

        Schema::create('addons', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('currency_id')->default(1);
            $table->string('plan_order_id')->nullable();
            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->string('store_id')->nullable();
            $table->string('month')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};