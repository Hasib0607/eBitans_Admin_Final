<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoicepurchases')) {
            return;
        }

        Schema::create('invoicepurchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_id')->nullable();
            $table->string('store_id')->nullable();
            $table->string('status')->nullable();
            $table->string('amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('seen')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoicepurchases');
    }
};