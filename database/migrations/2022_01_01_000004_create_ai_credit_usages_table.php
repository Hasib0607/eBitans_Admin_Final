<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_credit_usages')) {
            return;
        }

        Schema::create('ai_credit_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('user_id')->nullable()->default('NULL');
            $table->unsignedBigInteger('plan_id')->nullable()->default('NULL');
            $table->string('source', 80)->default('ai-fill');
            $table->unsignedInteger('actual_tokens_used')->default(0);
            $table->decimal('credits_used', 12, 2)->default(0.0);
            $table->longText('meta')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index('plan_id', 'ai_credit_usages_plan_id_index');
            $table->index('source', 'ai_credit_usages_source_index');
            $table->index(['store_id', 'created_at'], 'ai_credit_usages_store_id_created_at_index');
            $table->index('store_id', 'ai_credit_usages_store_id_index');
            $table->index('user_id', 'ai_credit_usages_user_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_credit_usages');
    }
};