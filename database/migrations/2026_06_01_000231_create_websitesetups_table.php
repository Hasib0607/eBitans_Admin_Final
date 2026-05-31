<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('websitesetups')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `websitesetups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) DEFAULT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `data_submit` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=Data not submit, 1=Data submit',
  `editor` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('websitesetups');
    }
};