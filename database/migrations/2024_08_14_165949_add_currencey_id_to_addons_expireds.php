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
            if (! Schema::hasTable('addons_expireds')) {
                return;
            }
            Schema::table('addons_expireds', function (Blueprint $table) {
                if (! Schema::hasColumn('addons_expireds', 'currency_id')) {
                    $table->bigInteger('currency_id')->default(1)->after('id')->index();
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
            if (! Schema::hasTable('addons_expireds')) {
                return;
            }
        }
    };
