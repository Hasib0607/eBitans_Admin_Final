<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `body` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `user_type` varchar(191) DEFAULT NULL,
  `user_id` varchar(191) DEFAULT NULL,
  `store_id` varchar(191) DEFAULT NULL,
  `link` varchar(191) DEFAULT NULL,
  `view` tinyint(4) NOT NULL DEFAULT 0,
  `conversation_id` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};