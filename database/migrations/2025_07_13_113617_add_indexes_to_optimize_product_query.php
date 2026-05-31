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
        if ($this->canIndex('products', ['position'], 'idx_position')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('position', 'idx_position');
            });
        }

        if ($this->canIndex('products', ['store_id', 'status'], 'idx_store_status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['store_id', 'status'], 'idx_store_status');
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
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if ($this->indexExists('products', 'idx_position')) {
                    $table->dropIndex('idx_position');
                }
                if ($this->indexExists('products', 'idx_store_status')) {
                    $table->dropIndex('idx_store_status');
                }
            });
        }
    }
};
