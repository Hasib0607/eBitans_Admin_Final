<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('paymentgateways')) {
            return;
        }

        Schema::create('paymentgateways', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->string('payment_company')->nullable();
            $table->string('app_key')->nullable();
            $table->string('app_secret')->nullable();
            $table->string('client_id', 191)->nullable();
            $table->string('ssl_store_id')->nullable();
            $table->string('ssl_store_password')->nullable();
            $table->string('api_username')->nullable();
            $table->string('api_password')->nullable();
            $table->string('merchant_id', 191)->nullable();
            $table->string('merchant_number', 191)->nullable();
            $table->longText('public_key')->nullable();
            $table->longText('private_key')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_password')->nullable();
            $table->bigInteger('store_id')->nullable()->default('NULL');
            $table->string('status')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paymentgateways');
    }
};