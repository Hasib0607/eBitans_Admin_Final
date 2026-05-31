<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('websitesetups')) {
            return;
        }

        Schema::create('websitesetups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->bigInteger('store_id')->nullable()->default('NULL');
            $table->bigInteger('customer_id')->nullable()->default('NULL');
            $table->string('status')->nullable();
            $table->tinyInteger('data_submit')->default(0);
            $table->bigInteger('editor')->nullable()->default('NULL');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('websitesetups');
    }
};