<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('saas_features')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `saas_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) NOT NULL,
  `name` varchar(190) NOT NULL,
  `type` enum('page','action','quota') NOT NULL DEFAULT 'action',
  `enabled_by_default` tinyint(1) NOT NULL DEFAULT 1,
  `default_limit` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `saas_features_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=773 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_features');
    }
};