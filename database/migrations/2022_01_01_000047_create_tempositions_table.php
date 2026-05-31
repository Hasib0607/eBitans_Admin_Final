<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tempositions')) {
            return;
        }

        Schema::create('tempositions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('position')->nullable()->default('NULL');
            $table->string('template_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index('template_id', 'idx_template_id');
            $table->index(['template_id', 'position'], 'idx_tempositions_template_position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tempositions');
    }
};