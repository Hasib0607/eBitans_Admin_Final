<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('digitalplans')) {
            return;
        }

        Schema::create('digitalplans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('subtitle')->nullable();
            $table->double('price')->default(0);
            $table->string('discount_type')->nullable();
            $table->double('onedis')->default(0);
            $table->decimal('threedis', 8, 2)->nullable()->default('NULL');
            $table->double('sixdis')->default(0);
            $table->double('twelvedis')->default(0);
            $table->double('twentyfourdis')->default(0);
            $table->string('page_setup')->nullable()->default('No');
            $table->bigInteger('static_content')->default(0);
            $table->bigInteger('video_content')->default(0);
            $table->bigInteger('gify_content')->default(0);
            $table->string('google_ad')->nullable()->default('No');
            $table->string('boosting_page')->default('No');
            $table->double('caption_writting')->default(0);
            $table->bigInteger('position')->default(0);
            $table->decimal('payment_processing_charge', 8, 2)->default(0.0);
            $table->decimal('monthly_chat_support', 8, 2)->default(0.0);
            $table->string('status')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digitalplans');
    }
};