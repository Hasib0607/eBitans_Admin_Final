<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('activities')) {
            return;
        }

        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('store_id')->nullable();
            $table->string('month')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};