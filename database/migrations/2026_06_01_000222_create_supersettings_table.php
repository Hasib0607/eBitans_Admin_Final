<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('supersettings')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `supersettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` mediumtext DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title2` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `img` mediumtext DEFAULT NULL,
  `discount` bigint(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('supersettings');
    }
};