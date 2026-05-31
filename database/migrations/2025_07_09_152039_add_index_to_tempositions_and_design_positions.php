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
        if (! $this->indexExists('tempositions', 'idx_template_id')) {
            Schema::table('tempositions', function (Blueprint $table) {
                $table->index('template_id', 'idx_template_id');
            });
        }

        if (! $this->indexExists('design_positions', 'idx_store_id')) {
            Schema::table('design_positions', function (Blueprint $table) {
                $table->index('store_id', 'idx_store_id');
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
        Schema::table('tempositions', function (Blueprint $table) {
            $table->dropIndex('idx_template_id');
        });

        Schema::table('design_positions', function (Blueprint $table) {
            $table->dropIndex('idx_store_id');
        });
    }
};
