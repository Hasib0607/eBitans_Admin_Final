<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_blog_keywords')) {
            return;
        }

        Schema::create('admin_blog_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_blog_keywords');
    }
};
