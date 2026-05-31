<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('branches')) {
            return;
        }

        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->double('tax')->default(0);
            $table->string('uid')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('store_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->string('status')->nullable();
            $table->string('staff_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};