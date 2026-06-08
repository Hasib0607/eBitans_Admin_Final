<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if ($this->canIndex('domains', ['name', 'status'], 'idx_domains_name_status')) {
            Schema::table('domains', function (Blueprint $table) {
                $table->index(['name', 'status'], 'idx_domains_name_status');
            });
        }

        if ($this->canIndex('domains', ['store_id'], 'idx_domains_store_id')) {
            Schema::table('domains', function (Blueprint $table) {
                $table->index('store_id', 'idx_domains_store_id');
            });
        }

        if ($this->canIndex('stores', ['url', 'expiry_date', 'store_status'], 'idx_stores_url_expiry_status')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->index(['url', 'expiry_date', 'store_status'], 'idx_stores_url_expiry_status');
            });
        }

        if ($this->canIndex('stores', ['slug', 'expiry_date', 'store_status'], 'idx_stores_slug_expiry_status')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->index(['slug', 'expiry_date', 'store_status'], 'idx_stores_slug_expiry_status');
            });
        }

        if ($this->canIndex('headersettings', ['store_id'], 'idx_headersettings_store_id')) {
            Schema::table('headersettings', function (Blueprint $table) {
                $table->index('store_id', 'idx_headersettings_store_id');
            });
        }

        if ($this->canIndex('buy_moduluses', ['store_id', 'modulus_id'], 'idx_buy_moduluses_store_module')) {
            Schema::table('buy_moduluses', function (Blueprint $table) {
                $table->index(['store_id', 'modulus_id'], 'idx_buy_moduluses_store_module');
            });
        }

        if ($this->canIndex('categories', ['store_id', 'parent', 'status'], 'idx_categories_store_parent_status')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->index(['store_id', 'parent', 'status'], 'idx_categories_store_parent_status');
            });
        }

        if ($this->canIndex('product_layouts', ['product_id'], 'idx_product_layouts_product_id')) {
            Schema::table('product_layouts', function (Blueprint $table) {
                $table->index('product_id', 'idx_product_layouts_product_id');
            });
        }
    }

    public function down(): void
    {
        $this->dropIndexIfExists('domains', 'idx_domains_name_status');
        $this->dropIndexIfExists('domains', 'idx_domains_store_id');
        $this->dropIndexIfExists('stores', 'idx_stores_url_expiry_status');
        $this->dropIndexIfExists('stores', 'idx_stores_slug_expiry_status');
        $this->dropIndexIfExists('headersettings', 'idx_headersettings_store_id');
        $this->dropIndexIfExists('buy_moduluses', 'idx_buy_moduluses_store_module');
        $this->dropIndexIfExists('categories', 'idx_categories_store_parent_status');
        $this->dropIndexIfExists('product_layouts', 'idx_product_layouts_product_id');
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

    private function dropIndexIfExists(string $table, string $index): void
    {
        if (! Schema::hasTable($table) || ! $this->indexExists($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($index) {
            $table->dropIndex($index);
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            return ! empty(DB::select('select 1 from pg_indexes where schemaname = current_schema() and indexname = ? limit 1', [$index]));
        }

        return ! empty(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]));
    }
};
