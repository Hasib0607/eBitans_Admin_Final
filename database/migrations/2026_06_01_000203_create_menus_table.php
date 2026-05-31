<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menus')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort` varchar(255) DEFAULT NULL,
  `custom_link` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT '0',
  `uid` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `creator` varchar(255) DEFAULT NULL,
  `editor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_menus_store_sort` (`store_id`,`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=33043 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};