<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_seed_batches')) {
            return;
        }

        Schema::create('ai_seed_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->string('mode', 20)->default('auto');
            $table->unsignedBigInteger('business_category_id')->nullable()->default('NULL');
            $table->string('image_ratio', 20)->nullable();
            $table->unsignedInteger('image_width')->nullable()->default('NULL');
            $table->unsignedInteger('image_height')->nullable()->default('NULL');
            $table->string('status', 30)->default('pending');
            $table->longText('blueprint')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index('business_category_id', 'ai_seed_batches_business_category_id_index');
            $table->index('mode', 'ai_seed_batches_mode_index');
            $table->index('status', 'ai_seed_batches_status_index');
            $table->index('store_id', 'ai_seed_batches_store_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_seed_batches');
    }
};