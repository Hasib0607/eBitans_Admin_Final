<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('testimonials')) {
            return;
        }

        Schema::create('testimonials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('occupation')->nullable();
            $table->mediumText('image')->nullable();
            $table->mediumText('feedback')->nullable();
            $table->string('status')->nullable();
            $table->string('position')->nullable();
            $table->string('uid')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('store_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};