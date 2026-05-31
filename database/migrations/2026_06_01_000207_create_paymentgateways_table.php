<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('paymentgateways')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `paymentgateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) DEFAULT NULL,
  `payment_company` varchar(255) DEFAULT NULL,
  `app_key` varchar(255) DEFAULT NULL,
  `app_secret` varchar(255) DEFAULT NULL,
  `client_id` varchar(191) DEFAULT NULL,
  `ssl_store_id` varchar(255) DEFAULT NULL,
  `ssl_store_password` varchar(255) DEFAULT NULL,
  `api_username` varchar(255) DEFAULT NULL,
  `api_password` varchar(255) DEFAULT NULL,
  `merchant_id` varchar(191) DEFAULT NULL,
  `merchant_number` varchar(191) DEFAULT NULL,
  `public_key` longtext DEFAULT NULL,
  `private_key` longtext DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=266 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('paymentgateways');
    }
};