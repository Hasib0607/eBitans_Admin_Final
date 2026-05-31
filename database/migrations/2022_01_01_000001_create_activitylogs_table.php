<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('activitylogs')) {
            return;
        }

        Schema::create('activitylogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid')->nullable();
            $table->string('ip')->nullable();
            $table->string('activity')->nullable();
            $table->string('store_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->tinyInteger('is_superadmin')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activitylogs');
    }
};