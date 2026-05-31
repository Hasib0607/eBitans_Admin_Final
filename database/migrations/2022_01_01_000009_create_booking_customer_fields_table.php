<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_customer_fields')) {
            return;
        }

        Schema::create('booking_customer_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->text('modulus_id');
            $table->text('name');
            $table->integer('tagId');
            $table->text('is_required');
            $table->text('store_id');
            $table->text('customer_id');
            $table->integer('is_checked');
            $table->text('uId');
            $table->integer('is_single');
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_customer_fields');
    }
};