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
        Schema::table('domains', function (Blueprint $table) {
            if (! Schema::hasColumn('domains', 'email')) {
                $table->string('email')->after('name')->nullable();
            }
            if (! Schema::hasColumn('domains', 'connect_status')) {
                $table->string('connect_status')->after('status')->nullable();
            }
            if (! Schema::hasColumn('domains', 'remark')) {
                $table->string('remark')->after('connect_status')->nullable();
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
        Schema::table('domains', function (Blueprint $table) {
            if (Schema::hasColumn('domains', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('domains', 'connect_status')) {
                $table->dropColumn('connect_status');
            }
            if (Schema::hasColumn('domains', 'remark')) {
                $table->dropColumn('remark');
            }
        });
    }
};
