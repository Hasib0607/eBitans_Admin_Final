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
            if (! Schema::hasColumn('headersettings', 'merchant_bkash')) {
                $table->string('merchant_bkash')->after('uddoktapay')->nullable();
            }
            if (! Schema::hasColumn('headersettings', 'merchant_nagad')) {
                $table->string('merchant_nagad')->after('merchant_bkash')->nullable();
            }
            if (! Schema::hasColumn('headersettings', 'merchant_rocket')) {
                $table->string('merchant_rocket')->after('merchant_nagad')->nullable();
            }

            if (! Schema::hasColumn('headersettings', 'merchant_bkash_text')) {
                $table->string('merchant_bkash_text')->after('uddoktapay_text')->default("Bkash");
            }
            if (! Schema::hasColumn('headersettings', 'merchant_nagad_text')) {
                $table->string('merchant_nagad_text')->after('merchant_bkash_text')->default("Nagad");
            }
            if (! Schema::hasColumn('headersettings', 'merchant_rocket_text')) {
                $table->string('merchant_rocket_text')->after('merchant_nagad_text')->default("Rocket");
            }

            if (! Schema::hasColumn('headersettings', 'balance_min_withdraw')) {
                $table->string('balance_min_withdraw')->after('custom_writing')->nullable()->default(1000);
            }
            if (! Schema::hasColumn('headersettings', 'balance_max_withdraw')) {
                $table->string('balance_max_withdraw')->after('balance_min_withdraw')->nullable();
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
            if (Schema::hasColumn('headersettings', 'merchant_bkash')) {
                $table->dropColumn('merchant_bkash');
            }
            if (Schema::hasColumn('headersettings', 'merchant_nagad')) {
                $table->dropColumn('merchant_nagad');
            }
            if (Schema::hasColumn('headersettings', 'merchant_rocket')) {
                $table->dropColumn('merchant_rocket');
            }
            if (Schema::hasColumn('headersettings', 'merchant_bkash_text')) {
                $table->dropColumn('merchant_bkash_text');
            }
            if (Schema::hasColumn('headersettings', 'merchant_nagad_text')) {
                $table->dropColumn('merchant_nagad_text');
            }
            if (Schema::hasColumn('headersettings', 'merchant_rocket_text')) {
                $table->dropColumn('merchant_rocket_text');
            }

            if (Schema::hasColumn('headersettings', 'balance_min_withdraw')) {
                $table->dropColumn('balance_min_withdraw');
            }
            if (Schema::hasColumn('headersettings', 'balance_max_withdraw')) {
                $table->dropColumn('balance_max_withdraw');
            }
        });
    }
};
