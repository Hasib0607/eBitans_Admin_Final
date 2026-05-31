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
        if (! Schema::hasTable('designlists')) {
            return;
        }
        Schema::table('designlists', function (Blueprint $table) {
            if (! Schema::hasColumn('designlists', 'bg_image')) {
                $table->string('bg_image')->after('image')->nullable();
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
        if (! Schema::hasTable('designlists')) {
            return;
        }
        Schema::table('designlists', function (Blueprint $table) {
            if (Schema::hasColumn('designlists', 'bg_image')) {
                $table->dropColumn('bg_image');
            }
        });
    }
};
