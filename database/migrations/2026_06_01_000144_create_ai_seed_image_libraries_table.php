<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_seed_image_libraries')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `ai_seed_image_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_category_id` bigint(20) unsigned DEFAULT NULL,
  `business_category_name` varchar(191) DEFAULT NULL,
  `category_slug` varchar(191) DEFAULT NULL,
  `subcategory_slug` varchar(191) DEFAULT NULL,
  `usage_type` varchar(30) NOT NULL DEFAULT 'product',
  `ratio_key` varchar(20) DEFAULT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `path` varchar(191) NOT NULL,
  `original_name` varchar(191) DEFAULT NULL,
  `alt_text` varchar(191) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_seed_img_usage_category_status_idx` (`usage_type`,`business_category_id`,`status`),
  KEY `ai_seed_image_libraries_business_category_id_index` (`business_category_id`),
  KEY `ai_seed_image_libraries_category_slug_index` (`category_slug`),
  KEY `ai_seed_image_libraries_subcategory_slug_index` (`subcategory_slug`),
  KEY `ai_seed_image_libraries_usage_type_index` (`usage_type`),
  KEY `ai_seed_image_libraries_ratio_key_index` (`ratio_key`),
  KEY `ai_seed_image_libraries_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_seed_image_libraries');
    }
};