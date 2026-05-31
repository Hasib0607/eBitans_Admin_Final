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
        Schema::table('headersettings', function (Blueprint $table) {
            if (! Schema::hasColumn('headersettings', 'map_address')) {
                $table->longText('map_address')->nullable()->after('address');
            }
            if (! Schema::hasColumn('headersettings', 'stock_out_qty')) {
                $table->longText('stock_out_qty')->nullable()->after('pagination');
            }
            if (! Schema::hasColumn('headersettings', 'order_sms')) {
                $table->longText('order_sms')->nullable()->after('stock_out_qty');
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
        Schema::table('headersettings', function (Blueprint $table) {
            $table->dropColumn(['map_address', 'stock_out_qty', 'order_sms']);
        });
    }
};
