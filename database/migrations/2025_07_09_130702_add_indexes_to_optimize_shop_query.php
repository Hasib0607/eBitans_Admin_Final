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
        if ($this->canIndex('stores', ['expiry_date'], 'idx_expiry')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->index('expiry_date', 'idx_expiry');
            });
        }

        if ($this->canIndex('products', ['regular_price'], 'idx_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('regular_price', 'idx_price');
            });
        }

        if ($this->canIndex('reviews', ['product_id'], 'idx_product_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->index('product_id', 'idx_product_id');
            });
        }

    }

    private function canIndex(string $table, array $columns, string $index): bool
    {
        if (! Schema::hasTable($table) || $this->indexExists($table, $index)) {
            return false;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return false;
            }
        }

        return true;
    }

    private function indexExists(string $table, string $index): bool
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            return ! empty(DB::select('select 1 from pg_indexes where schemaname = current_schema() and indexname = ? limit 1', [$index]));
        }

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
                if ($this->indexExists('stores', 'idx_expiry')) {
                    $table->dropIndex('idx_expiry');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if ($this->indexExists('products', 'idx_price')) {
                    $table->dropIndex('idx_price');
                }
            });
        }

        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if ($this->indexExists('reviews', 'idx_product_id')) {
                    $table->dropIndex('idx_product_id');
                }
            });
        }

    }
};
