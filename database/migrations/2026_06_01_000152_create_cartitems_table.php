<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cartitems')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `cartitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `variant_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `custome_order` varchar(191) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `price` bigint(20) DEFAULT NULL,
  `discount` bigint(20) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `volume` varchar(255) DEFAULT NULL,
  `expired` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `customer_id` varchar(255) DEFAULT NULL,
  `bid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('cartitems');
    }
};