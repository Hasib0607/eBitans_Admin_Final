<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('messages')) {
            return;
        }

        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tokenid')->nullable();
            $table->string('name')->nullable();
            $table->string('message')->nullable();
            $table->mediumText('image')->nullable();
            $table->string('store_id')->nullable();
            $table->string('uid')->nullable();
            $table->string('send_id')->nullable();
            $table->string('receive_id')->nullable();
            $table->bigInteger('seen')->nullable()->default(0);
            $table->integer('view')->default(0);
            $table->string('type')->nullable();
            $table->string('session_id')->nullable();
            $table->string('session')->nullable()->default('deactive');
            $table->timestamp('session_end')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};