<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('boostings')) {
            return;
        }

        Schema::create('boostings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->double('amount')->default(0);
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('status')->nullable();
            $table->string('content')->nullable();
            $table->mediumText('note')->nullable();
            $table->bigInteger('reached')->default(0);
            $table->string('uid')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('store_id')->nullable();
            $table->string('editor')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boostings');
    }
};