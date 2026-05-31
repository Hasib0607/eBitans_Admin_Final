<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_seed_batches')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `ai_seed_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `mode` varchar(20) NOT NULL DEFAULT 'auto',
  `business_category_id` bigint(20) unsigned DEFAULT NULL,
  `image_ratio` varchar(20) DEFAULT NULL,
  `image_width` int(10) unsigned DEFAULT NULL,
  `image_height` int(10) unsigned DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `blueprint` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`blueprint`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_seed_batches_store_id_index` (`store_id`),
  KEY `ai_seed_batches_mode_index` (`mode`),
  KEY `ai_seed_batches_business_category_id_index` (`business_category_id`),
  KEY `ai_seed_batches_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_seed_batches');
    }
};