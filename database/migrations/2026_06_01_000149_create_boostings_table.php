<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('boostings')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `boostings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `from` date DEFAULT NULL,
  `to` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `note` mediumtext DEFAULT NULL,
  `reached` bigint(20) NOT NULL DEFAULT 0,
  `uid` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `editor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('boostings');
    }
};