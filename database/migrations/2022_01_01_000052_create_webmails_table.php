<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('webmails')) {
            return;
        }

        Schema::create('webmails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->bigInteger('uid')->nullable()->default('NULL');
            $table->bigInteger('store_id')->nullable()->default('NULL');
            $table->bigInteger('customer_id')->nullable()->default('NULL');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webmails');
    }
};