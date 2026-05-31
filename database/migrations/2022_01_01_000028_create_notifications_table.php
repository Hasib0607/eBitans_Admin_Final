<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            return;
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191)->nullable();
            $table->string('body', 191)->nullable();
            $table->string('type', 191)->nullable();
            $table->string('user_type', 191)->nullable();
            $table->string('user_id', 191)->nullable();
            $table->string('store_id', 191)->nullable();
            $table->string('link', 191)->nullable();
            $table->tinyInteger('view')->default(0);
            $table->string('conversation_id', 191)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};