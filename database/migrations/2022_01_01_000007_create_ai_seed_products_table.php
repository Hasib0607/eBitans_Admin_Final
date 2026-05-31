<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_seed_products')) {
            return;
        }

        Schema::create('ai_seed_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('product_id')->nullable()->default('NULL');
            $table->unsignedBigInteger('source_image_id')->nullable()->default('NULL');
            $table->string('generated_image_path', 191)->nullable();
            $table->tinyInteger('is_demo')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index('batch_id', 'ai_seed_products_batch_id_index');
            $table->index('is_demo', 'ai_seed_products_is_demo_index');
            $table->index('product_id', 'ai_seed_products_product_id_index');
            $table->index('source_image_id', 'ai_seed_products_source_image_id_index');
            $table->index('store_id', 'ai_seed_products_store_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_seed_products');
    }
};