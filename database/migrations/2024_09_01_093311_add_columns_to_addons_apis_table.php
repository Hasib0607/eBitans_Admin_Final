<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            if (! Schema::hasColumn('addons_apis', 'usd_price')) {
                $table->string('usd_price')->nullable()->after('price');
            }
            if (! Schema::hasColumn('addons_apis', 'usd_offer_price')) {
                $table->string('usd_offer_price')->nullable()->after('usd_price');
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
            if (Schema::hasColumn('addons_apis', 'usd_price')) {
                $table->dropColumn('usd_price');
            }
            if (Schema::hasColumn('addons_apis', 'usd_offer_price')) {
                $table->dropColumn('usd_offer_price');
            }
        });
    }
};
