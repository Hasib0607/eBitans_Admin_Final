<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('designlists')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `designlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `title_color` varchar(250) DEFAULT NULL,
  `button` varchar(250) DEFAULT NULL,
  `image_description` varchar(250) DEFAULT NULL,
  `font_name` varchar(191) DEFAULT NULL,
  `ai_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ai_preferences`)),
  `subtitle` varchar(250) DEFAULT NULL,
  `subtitle_color` varchar(20) DEFAULT NULL,
  `button_color` varchar(20) DEFAULT NULL,
  `button1` varchar(191) DEFAULT NULL,
  `button1_color` varchar(191) DEFAULT NULL,
  `button1_bg_color` varchar(191) DEFAULT NULL,
  `button_bg_color` varchar(191) DEFAULT NULL,
  `link` varchar(191) DEFAULT NULL,
  `image` mediumtext DEFAULT NULL,
  `mobile_image` varchar(191) DEFAULT NULL,
  `bg_image` varchar(191) DEFAULT NULL,
  `bg_images` longtext DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=845 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('designlists');
    }
};