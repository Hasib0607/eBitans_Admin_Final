<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('paymenttokens')) {
            return;
        }

        Schema::create('paymenttokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->string('uid')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paymenttokens');
    }
};