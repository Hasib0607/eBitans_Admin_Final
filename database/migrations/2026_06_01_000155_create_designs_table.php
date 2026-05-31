<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('designs')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `designs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `header_color` varchar(255) DEFAULT NULL,
  `text_color` varchar(255) DEFAULT NULL,
  `section_settings` longtext DEFAULT NULL,
  `hero_slider` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `banner_bottom` varchar(255) DEFAULT NULL,
  `feature_category` varchar(255) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `feature_product` varchar(255) DEFAULT NULL,
  `best_sell_product` varchar(255) DEFAULT NULL,
  `new_arrival` varchar(255) DEFAULT NULL,
  `testimonial` varchar(255) DEFAULT NULL,
  `youtube` varchar(191) DEFAULT 'none',
  `announcement` varchar(191) DEFAULT 'null',
  `about` varchar(191) DEFAULT 'null',
  `newsletter` varchar(191) DEFAULT 'null',
  `brand` varchar(191) DEFAULT 'none',
  `footer` varchar(255) DEFAULT NULL,
  `auth` varchar(255) DEFAULT 'one',
  `single_product_page` varchar(255) DEFAULT 'default',
  `shop_page` varchar(255) DEFAULT 'default',
  `checkout_page` varchar(255) DEFAULT 'default',
  `login_page` varchar(255) DEFAULT 'default',
  `profile_page` varchar(255) DEFAULT 'default',
  `invoice` varchar(255) DEFAULT 'default',
  `product_card` varchar(255) DEFAULT 'default',
  `product_modal` varchar(255) DEFAULT 'default',
  `preloader` varchar(255) DEFAULT 'default',
  `mobile_bottom_menu` varchar(255) DEFAULT 'default',
  `offer` varchar(255) DEFAULT 'default',
  `blog` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `template_id` varchar(255) DEFAULT '0',
  `uid` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `creator` varchar(255) DEFAULT NULL,
  `editor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_designs_store` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};