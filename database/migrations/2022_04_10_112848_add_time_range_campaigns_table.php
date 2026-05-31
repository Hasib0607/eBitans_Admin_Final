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
        Schema::table('campaigns', function (Blueprint $table) {
            if (! Schema::hasColumn('campaigns', 'length_type')) {
                $table->string('length_type')->nullable();
            }
            if (! Schema::hasColumn('campaigns', 'specific_dates')) {
                $table->string('specific_dates')->nullable();
            }
            if (! Schema::hasColumn('campaigns', 'repeat_dates')) {
                $table->string('repeat_dates')->nullable();
            }
            if (! Schema::hasColumn('campaigns', 'start_time')) {
                $table->string('start_time')->nullable();
            }
            if (! Schema::hasColumn('campaigns', 'end_time')) {
                $table->string('end_time')->nullable();
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
        Schema::table('campaigns', function (Blueprint $table) {
            //
        });
    }
};
