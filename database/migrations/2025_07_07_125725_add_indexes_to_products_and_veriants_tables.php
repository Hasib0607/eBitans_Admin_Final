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
        Schema::table('products_and_veriants_tables', function (Blueprint $table) {
            Schema::table('products', function (Blueprint $table) {
                if (! $this->indexExists('products', 'idx_status')) {
                    $table->index('status', 'idx_status');
                }
                if (! $this->indexExists('products', 'idx_category')) {
                    $table->index('category', 'idx_category');
                }
                if (! $this->indexExists('products', 'idx_sub')) {
                    $table->index('subcategory', 'idx_sub');
                }
                if (! $this->indexExists('products', 'idx_brand')) {
                    $table->index('brand', 'idx_brand');
                }
                if (! $this->indexExists('products', 'idx_store')) {
                    $table->index('store_id', 'idx_store');
                }
            });

            Schema::table('veriants', function (Blueprint $table) {
                if (! $this->indexExists('veriants', 'idx_pid_color')) {
                    $table->index(['pid', 'color'], 'idx_pid_color');
                }
            });
        });
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
        Schema::table('products_and_veriants_tables', function (Blueprint $table) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('idx_status');
                $table->dropIndex('idx_category');
                $table->dropIndex('idx_sub');
                $table->dropIndex('idx_brand');
                $table->dropIndex('idx_store');
            });

            Schema::table('veriants', function (Blueprint $table) {
                $table->dropIndex('idx_pid_color');
            });
        });
    }
};
