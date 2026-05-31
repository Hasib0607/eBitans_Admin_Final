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
        Schema::table('admin_blogs', function (Blueprint $table) {
            if (! Schema::hasColumn('admin_blogs', 'permalink')) {
                $table->string('permalink')->nullable()->after('popular');
            }
            if (! Schema::hasColumn('admin_blogs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('permalink');
            }
            if (! Schema::hasColumn('admin_blogs', 'store_id')) {
                $table->unsignedBigInteger('store_id')->nullable()->after('user_id');
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
        Schema::table('admin_blogs', function (Blueprint $table) {
            $table->dropColumn(['permalink', 'user_id', 'store_id']);
        });
    }
};
