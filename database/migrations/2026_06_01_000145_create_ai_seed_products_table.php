<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_seed_products')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `ai_seed_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `store_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `source_image_id` bigint(20) unsigned DEFAULT NULL,
  `generated_image_path` varchar(191) DEFAULT NULL,
  `is_demo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_seed_products_batch_id_index` (`batch_id`),
  KEY `ai_seed_products_store_id_index` (`store_id`),
  KEY `ai_seed_products_product_id_index` (`product_id`),
  KEY `ai_seed_products_source_image_id_index` (`source_image_id`),
  KEY `ai_seed_products_is_demo_index` (`is_demo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_seed_products');
    }
};