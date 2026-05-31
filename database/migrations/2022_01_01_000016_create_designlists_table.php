<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('designlists')) {
            return;
        }

        Schema::create('designlists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->string('value')->nullable();
            $table->string('title', 250)->nullable();
            $table->string('title_color', 250)->nullable();
            $table->string('button', 250)->nullable();
            $table->string('image_description', 250)->nullable();
            $table->string('font_name', 191)->nullable();
            $table->longText('ai_preferences')->nullable();
            $table->string('subtitle', 250)->nullable();
            $table->string('subtitle_color', 20)->nullable();
            $table->string('button_color', 20)->nullable();
            $table->string('button1', 191)->nullable();
            $table->string('button1_color', 191)->nullable();
            $table->string('button1_bg_color', 191)->nullable();
            $table->string('button_bg_color', 191)->nullable();
            $table->string('link', 191)->nullable();
            $table->mediumText('image')->nullable();
            $table->string('mobile_image', 191)->nullable();
            $table->string('bg_image', 191)->nullable();
            $table->longText('bg_images')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designlists');
    }
};