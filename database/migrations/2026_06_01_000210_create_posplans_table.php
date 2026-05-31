<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('posplans')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `posplans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `usd_price` double NOT NULL DEFAULT 0,
  `discount_type` varchar(255) DEFAULT NULL,
  `onedis` double DEFAULT NULL,
  `threedis` decimal(8,2) DEFAULT NULL,
  `sixdis` double DEFAULT NULL,
  `twelvedis` double DEFAULT NULL,
  `twentyfourdis` double DEFAULT NULL,
  `branch` bigint(20) DEFAULT NULL,
  `product` bigint(20) DEFAULT NULL,
  `staff` bigint(20) DEFAULT NULL,
  `order` bigint(20) DEFAULT NULL,
  `advance_report` varchar(255) DEFAULT 'No',
  `inventory` varchar(255) DEFAULT 'No',
  `pos_setup` varchar(255) DEFAULT 'No',
  `position` bigint(20) DEFAULT 0,
  `payment_processing_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `monthly_chat_support` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('posplans');
    }
};