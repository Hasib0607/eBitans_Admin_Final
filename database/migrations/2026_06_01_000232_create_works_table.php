<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('works')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `is_superadmin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=435 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};