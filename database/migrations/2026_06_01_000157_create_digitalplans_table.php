<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('digitalplans')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `digitalplans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `price` double NOT NULL DEFAULT 0,
  `discount_type` varchar(255) DEFAULT NULL,
  `onedis` double NOT NULL DEFAULT 0,
  `threedis` decimal(8,2) DEFAULT NULL,
  `sixdis` double NOT NULL DEFAULT 0,
  `twelvedis` double NOT NULL DEFAULT 0,
  `twentyfourdis` double NOT NULL DEFAULT 0,
  `page_setup` varchar(255) DEFAULT 'No',
  `static_content` bigint(20) NOT NULL DEFAULT 0,
  `video_content` bigint(20) NOT NULL DEFAULT 0,
  `gify_content` bigint(20) NOT NULL DEFAULT 0,
  `google_ad` varchar(255) DEFAULT 'No',
  `boosting_page` varchar(255) NOT NULL DEFAULT 'No',
  `caption_writting` double NOT NULL DEFAULT 0,
  `position` bigint(20) NOT NULL DEFAULT 0,
  `payment_processing_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `monthly_chat_support` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('digitalplans');
    }
};