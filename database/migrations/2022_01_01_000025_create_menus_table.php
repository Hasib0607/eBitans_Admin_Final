<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menus')) {
            return;
        }

        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('sort')->nullable();
            $table->string('custom_link', 191)->nullable();
            $table->string('status', 191)->default('0');
            $table->string('uid')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('store_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index(['store_id', 'sort'], 'idx_menus_store_sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};