<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('plan_entitlements')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `plan_entitlements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` bigint(20) unsigned NOT NULL,
  `feature_key` varchar(120) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `limit_value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plan_entitlements_plan_id_feature_key_unique` (`plan_id`,`feature_key`),
  KEY `plan_entitlements_feature_key_index` (`feature_key`)
) ENGINE=InnoDB AUTO_INCREMENT=823 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_entitlements');
    }
};