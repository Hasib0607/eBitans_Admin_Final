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
            if (! Schema::hasColumn('designlists', 'link')) {
                $table->string('link')->after('button_bg_color')->nullable();
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
            if (Schema::hasColumn('designlists', 'link')) {
                $table->dropColumn('link');
            }
        });
    }
};
