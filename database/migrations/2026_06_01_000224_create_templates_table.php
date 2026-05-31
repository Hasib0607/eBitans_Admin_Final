<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('templates')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_amount` varchar(255) DEFAULT NULL,
  `feature_image` mediumtext DEFAULT NULL,
  `main_image` mediumtext DEFAULT NULL,
  `mobile_image` varchar(191) DEFAULT NULL,
  `liveurl` varchar(255) DEFAULT NULL,
  `short_description` mediumtext DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `slider` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `banner_bottom` varchar(255) DEFAULT NULL,
  `feature_category` varchar(255) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `feature_product` varchar(255) DEFAULT NULL,
  `best_sell_product` varchar(255) DEFAULT NULL,
  `new_arrival` varchar(255) DEFAULT NULL,
  `testimonial` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `footer` varchar(255) DEFAULT NULL,
  `auth` varchar(255) DEFAULT NULL,
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
  `blog` varchar(191) DEFAULT NULL,
  `contact` varchar(191) DEFAULT NULL,
  `announcement` varchar(191) DEFAULT NULL,
  `about` varchar(191) DEFAULT NULL,
  `newsletter` varchar(191) DEFAULT NULL,
  `brand` varchar(191) DEFAULT NULL,
  `is_premium` varchar(255) DEFAULT NULL,
  `review` double NOT NULL DEFAULT 0,
  `reviewer` int(11) NOT NULL DEFAULT 0,
  `downlad` double NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT NULL,
  `position` bigint(20) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};