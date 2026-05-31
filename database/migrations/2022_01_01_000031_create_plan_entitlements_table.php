<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('plan_entitlements')) {
            return;
        }

        Schema::create('plan_entitlements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('plan_id');
            $table->string('feature_key', 120);
            $table->tinyInteger('is_enabled')->default(1);
            $table->integer('limit_value')->nullable()->default('NULL');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index('feature_key', 'plan_entitlements_feature_key_index');
            $table->unique(['plan_id', 'feature_key'], 'plan_entitlements_plan_id_feature_key_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_entitlements');
    }
};