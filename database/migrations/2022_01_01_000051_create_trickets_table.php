<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trickets')) {
            return;
        }

        Schema::create('trickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->mediumText('message')->nullable();
            $table->mediumText('image')->nullable();
            $table->string('sender')->nullable();
            $table->string('seen')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trickets');
    }
};