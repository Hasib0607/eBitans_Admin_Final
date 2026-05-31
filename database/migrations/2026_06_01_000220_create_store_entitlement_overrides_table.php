<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('store_entitlement_overrides')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `store_entitlement_overrides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `feature_key` varchar(120) NOT NULL,
  `is_enabled` tinyint(1) DEFAULT NULL,
  `limit_value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_entitlement_overrides_store_id_feature_key_unique` (`store_id`,`feature_key`),
  KEY `store_entitlement_overrides_feature_key_index` (`feature_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('store_entitlement_overrides');
    }
};