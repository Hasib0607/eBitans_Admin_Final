<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_credit_usages')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `ai_credit_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `source` varchar(80) NOT NULL DEFAULT 'ai-fill',
  `actual_tokens_used` int(10) unsigned NOT NULL DEFAULT 0,
  `credits_used` decimal(12,2) NOT NULL DEFAULT 0.00,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_credit_usages_store_id_created_at_index` (`store_id`,`created_at`),
  KEY `ai_credit_usages_store_id_index` (`store_id`),
  KEY `ai_credit_usages_user_id_index` (`user_id`),
  KEY `ai_credit_usages_plan_id_index` (`plan_id`),
  KEY `ai_credit_usages_source_index` (`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_credit_usages');
    }
};