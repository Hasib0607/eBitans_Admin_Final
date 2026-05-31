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
        Schema::table('addresses', function (Blueprint $table) {
            if (! Schema::hasColumn('addresses', 'email')) {
                $table->string('email')->after('phone')->nullable();
            }
            if (! Schema::hasColumn('addresses', 'note')) {
                $table->string('note')->after('address')->nullable();
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
        Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasColumn('addresses', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('addresses', 'note')) {
                $table->dropColumn('note');
            }
        });
    }


};
