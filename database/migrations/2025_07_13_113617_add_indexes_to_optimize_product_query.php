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
        Schema::table('products', function (Blueprint $table) {
            if (! $this->indexExists('products', 'idx_position')) {
                $table->index('position', 'idx_position'); // Index on 'position'
            }
            if (! $this->indexExists('products', 'idx_store_status')) {
                $table->index(['store_id', 'status'], 'idx_store_status'); // Composite index
            }
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_position');
            $table->dropIndex('idx_store_status');
        });
    }
};
