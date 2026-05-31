<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('messages')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tokenid` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `image` mediumtext DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `send_id` varchar(255) DEFAULT NULL,
  `receive_id` varchar(255) DEFAULT NULL,
  `seen` bigint(20) DEFAULT 0,
  `view` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `session` varchar(255) DEFAULT 'deactive',
  `session_end` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5094 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};