<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('refcodeuses')) {
            return;
        }

        Schema::create('refcodeuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user')->nullable();
            $table->string('code')->nullable();
            $table->string('point')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refcodeuses');
    }
};