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
        Schema::table('admin_blog_types', function (Blueprint $table) {
            if (! Schema::hasColumn('admin_blog_types', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('status');
            }
            if (! Schema::hasColumn('admin_blog_types', 'store_id')) {
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
        Schema::table('admin_blog_types', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'store_id']);
        });
    }
};
