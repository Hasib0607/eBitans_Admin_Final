<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('superroles')) {
            return;
        }

        Schema::create('superroles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('permission')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('superroles');
    }
};