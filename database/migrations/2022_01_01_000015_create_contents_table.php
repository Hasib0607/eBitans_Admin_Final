<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contents')) {
            return;
        }

        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->mediumText('details')->nullable();
            $table->string('type')->nullable();
            $table->string('content', 256)->nullable();
            $table->mediumText('note')->nullable();
            $table->string('store_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};