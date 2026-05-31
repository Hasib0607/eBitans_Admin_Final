<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('superstaffs')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `superstaffs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `active_store` bigint(20) unsigned DEFAULT NULL,
  `new_commission` decimal(8,2) NOT NULL DEFAULT 10.00,
  `renew_commission` decimal(8,2) NOT NULL DEFAULT 5.00,
  `setup_commission` decimal(8,2) NOT NULL DEFAULT 5.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('superstaffs');
    }
};