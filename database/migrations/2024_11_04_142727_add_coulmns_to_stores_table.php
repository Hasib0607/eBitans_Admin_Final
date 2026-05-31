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
        if (! Schema::hasTable('stores')) {
            return;
        }
        Schema::table('stores', function (Blueprint $table) {
            if (! Schema::hasColumn('stores', 'pay_mail_status')) {
                $table->tinyInteger('pay_mail_status')->after('call_status')->default(0);
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
        if (! Schema::hasTable('stores')) {
            return;
        }
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'pay_mail_status')) {
                $table->dropColumn('pay_mail_status');
            }
        });
    }
};
