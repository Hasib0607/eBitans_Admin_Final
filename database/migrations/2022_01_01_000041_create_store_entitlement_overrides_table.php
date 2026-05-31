<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('store_entitlement_overrides')) {
            return;
        }

        Schema::create('store_entitlement_overrides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->string('feature_key', 120);
            $table->tinyInteger('is_enabled')->nullable()->default('NULL');
            $table->integer('limit_value')->nullable()->default('NULL');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index('feature_key', 'store_entitlement_overrides_feature_key_index');
            $table->unique(['store_id', 'feature_key'], 'store_entitlement_overrides_store_id_feature_key_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_entitlement_overrides');
    }
};