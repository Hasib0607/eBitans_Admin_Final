<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_tags')) {
            return;
        }

        Schema::create('booking_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_tags');
    }
};