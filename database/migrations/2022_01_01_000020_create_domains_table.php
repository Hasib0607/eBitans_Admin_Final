<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('domains')) {
            return;
        }

        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email', 191)->nullable();
            $table->string('status')->nullable();
            $table->string('connect_status', 191)->nullable();
            $table->string('remark')->nullable();
            $table->tinyInteger('buy_domain')->default(0);
            $table->tinyInteger('deleteRequest')->nullable()->default('NULL');
            $table->string('uid')->nullable();
            $table->string('store_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->integer('view')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};