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
        if (! Schema::hasTable('addons_apis')) {
            return;
        }
        Schema::table('addons_apis', function (Blueprint $table) {
            if (! Schema::hasColumn('addons_apis', 'position')) {
                $table->tinyInteger('position')->after('status')->default(0);
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
        if (! Schema::hasTable('addons_apis')) {
            return;
        }
        Schema::table('addons_apis', function (Blueprint $table) {
            if (Schema::hasColumn('addons_apis', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};
