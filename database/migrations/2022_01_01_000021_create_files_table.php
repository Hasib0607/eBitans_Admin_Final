<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('files')) {
            return;
        }

        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version_name');
            $table->string('build_js');
            $table->string('build_css');
            $table->string('file_name');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};