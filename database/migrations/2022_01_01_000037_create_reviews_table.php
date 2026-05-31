<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reviews')) {
            return;
        }

        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('name')->nullable();
            $table->mediumText('comment')->nullable();
            $table->decimal('rating', 10, 2)->nullable()->default('NULL');
            $table->string('uid')->nullable();
            $table->string('store_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index('product_id', 'idx_product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};