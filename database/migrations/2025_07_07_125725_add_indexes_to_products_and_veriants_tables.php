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
        if ($this->canIndex('products', ['status'], 'idx_status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('status', 'idx_status');
            });
        }

        if ($this->canIndex('products', ['category'], 'idx_category')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('category', 'idx_category');
            });
        }

        if ($this->canIndex('products', ['subcategory'], 'idx_sub')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('subcategory', 'idx_sub');
            });
        }

        if ($this->canIndex('products', ['brand'], 'idx_brand')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('brand', 'idx_brand');
            });
        }

        if ($this->canIndex('products', ['store_id'], 'idx_store')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('store_id', 'idx_store');
            });
        }

        if ($this->canIndex('veriants', ['pid', 'color'], 'idx_pid_color')) {
            Schema::table('veriants', function (Blueprint $table) {
                $table->index(['pid', 'color'], 'idx_pid_color');
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
                if ($this->indexExists('products', 'idx_status')) {
                    $table->dropIndex('idx_status');
                }
                if ($this->indexExists('products', 'idx_category')) {
                    $table->dropIndex('idx_category');
                }
                if ($this->indexExists('products', 'idx_sub')) {
                    $table->dropIndex('idx_sub');
                }
                if ($this->indexExists('products', 'idx_brand')) {
                    $table->dropIndex('idx_brand');
                }
                if ($this->indexExists('products', 'idx_store')) {
                    $table->dropIndex('idx_store');
                }
            });
        }

        if (Schema::hasTable('veriants')) {
            Schema::table('veriants', function (Blueprint $table) {
                if ($this->indexExists('veriants', 'idx_pid_color')) {
                    $table->dropIndex('idx_pid_color');
                }
            });
        }
    }
};
