<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('resetotps')) {
            return;
        }

        Schema::create('resetotps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone')->nullable();
            $table->string('email', 191)->nullable();
            $table->string('code')->nullable();
            $table->date('start_min')->nullable();
            $table->timestamp('exp_min')->nullable();
            $table->string('token')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resetotps');
    }
};