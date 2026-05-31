<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mobileapps')) {
            return;
        }

        Schema::create('mobileapps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->mediumText('image')->nullable();
            $table->string('store_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->string('status')->nullable();
            $table->string('url')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobileapps');
    }
};