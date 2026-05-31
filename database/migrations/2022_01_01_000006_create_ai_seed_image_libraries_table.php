<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_seed_image_libraries')) {
            return;
        }

        Schema::create('ai_seed_image_libraries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_category_id')->nullable()->default('NULL');
            $table->string('business_category_name', 191)->nullable();
            $table->string('category_slug', 191)->nullable();
            $table->string('subcategory_slug', 191)->nullable();
            $table->string('usage_type', 30)->default('product');
            $table->string('ratio_key', 20)->nullable();
            $table->unsignedInteger('width')->nullable()->default('NULL');
            $table->unsignedInteger('height')->nullable()->default('NULL');
            $table->string('path', 191);
            $table->string('original_name', 191)->nullable();
            $table->string('alt_text', 191)->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index('business_category_id', 'ai_seed_image_libraries_business_category_id_index');
            $table->index('category_slug', 'ai_seed_image_libraries_category_slug_index');
            $table->index('ratio_key', 'ai_seed_image_libraries_ratio_key_index');
            $table->index('status', 'ai_seed_image_libraries_status_index');
            $table->index('subcategory_slug', 'ai_seed_image_libraries_subcategory_slug_index');
            $table->index('usage_type', 'ai_seed_image_libraries_usage_type_index');
            $table->index(['usage_type', 'business_category_id', 'status'], 'ai_seed_img_usage_category_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_seed_image_libraries');
    }
};