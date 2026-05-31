<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('toptools')) {
            return;
        }

        Schema::create('toptools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->mediumText('image')->nullable();
            $table->string('url')->nullable();
            $table->integer('count')->nullable()->default('NULL');
            $table->string('uid')->nullable();
            $table->string('store_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toptools');
    }
};