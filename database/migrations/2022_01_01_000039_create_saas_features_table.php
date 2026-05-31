<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('saas_features')) {
            return;
        }

        Schema::create('saas_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 120);
            $table->string('name', 190);
            $table->string('type', 6)->default('action');
            $table->tinyInteger('enabled_by_default')->default(1);
            $table->integer('default_limit')->nullable()->default('NULL');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique('key', 'saas_features_key_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_features');
    }
};