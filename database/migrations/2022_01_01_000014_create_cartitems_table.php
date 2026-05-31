<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cartitems')) {
            return;
        }

        Schema::create('cartitems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('image')->nullable();
            $table->string('variant_id')->nullable();
            $table->string('name')->nullable();
            $table->string('custome_order', 191)->nullable();
            $table->string('quantity')->nullable();
            $table->bigInteger('price')->nullable()->default('NULL');
            $table->bigInteger('discount')->nullable()->default('NULL');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('unit')->nullable();
            $table->string('volume')->nullable();
            $table->date('expired')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('customer_id')->nullable();
            $table->bigInteger('bid')->nullable()->default('NULL');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cartitems');
    }
};