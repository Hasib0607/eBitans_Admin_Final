<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('domains')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `connect_status` varchar(191) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `buy_domain` tinyint(4) NOT NULL DEFAULT 0,
  `deleteRequest` tinyint(4) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `creator` varchar(255) DEFAULT NULL,
  `editor` varchar(255) DEFAULT NULL,
  `view` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16076 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};