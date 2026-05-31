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
        Schema::table('superstaffs', function (Blueprint $table) {
            if (! Schema::hasColumn('superstaffs', 'active_store')) {
                $table->unsignedBigInteger('active_store')->after('status')->nullable();
            }
            if (! Schema::hasColumn('superstaffs', 'new_commission')) {
                $table->decimal('new_commission')->after('active_store')->default(10);
            }
            if (! Schema::hasColumn('superstaffs', 'renew_commission')) {
                $table->decimal('renew_commission')->after('new_commission')->default(5);
            }
            if (! Schema::hasColumn('superstaffs', 'setup_commission')) {
                $table->decimal('setup_commission')->after('renew_commission')->default(5);
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
        Schema::table('superstaffs', function (Blueprint $table) {
            if (Schema::hasColumn('superstaffs', 'active_store')) {
                $table->dropColumn('active_store');
            }
            if (Schema::hasColumn('superstaffs', 'new_commission')) {
                $table->dropColumn('new_commission');
            }
            if (Schema::hasColumn('superstaffs', 'renew_commission')) {
                $table->dropColumn('renew_commission');
            }
            if (Schema::hasColumn('superstaffs', 'setup_commission')) {
                $table->dropColumn('setup_commission');
            }
        });
    }
};
