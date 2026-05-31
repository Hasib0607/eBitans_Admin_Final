<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stores') && ! $this->indexExists('stores', 'idx_expiry')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->index('expiry_date', 'idx_expiry');
            });
        }

        if (! $this->indexExists('products', 'idx_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('regular_price', 'idx_price');
            });
        }

        if (! $this->indexExists('reviews', 'idx_product_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->index('product_id', 'idx_product_id');
            });
        }

    }

    private function indexExists(string $table, string $index): bool
    {
        return ! empty(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropIndex('idx_expiry');
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_price');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_product_id');
        });

    }
};
