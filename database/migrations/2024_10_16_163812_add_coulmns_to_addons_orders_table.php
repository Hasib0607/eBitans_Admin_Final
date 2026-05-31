<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('addons_orders')) {
            return;
        }
        Schema::table('addons_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('addons_orders', 'package')) {
                $table->longText('package')->after('addons')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('addons_orders')) {
            return;
        }
        Schema::table('addons_orders', function (Blueprint $table) {
            if (Schema::hasColumn('addons_orders', 'package')) {
                $table->dropColumn('package');
            }
        });
    }
};
