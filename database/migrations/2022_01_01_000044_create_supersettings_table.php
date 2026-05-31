<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('supersettings')) {
            return;
        }

        Schema::create('supersettings', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('image')->nullable();
            $table->string('title')->nullable();
            $table->string('title2')->nullable();
            $table->string('subtitle')->nullable();
            $table->mediumText('img')->nullable();
            $table->bigInteger('discount')->nullable()->default('NULL');
            $table->tinyInteger('status')->nullable()->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supersettings');
    }
};