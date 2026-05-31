<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('themecustomizes')) {
            return;
        }

        Schema::create('themecustomizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('theme')->nullable();
            $table->mediumText('details');
            $table->string('phone')->nullable();
            $table->string('store_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('seen')->nullable();
            $table->string('token')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themecustomizes');
    }
};