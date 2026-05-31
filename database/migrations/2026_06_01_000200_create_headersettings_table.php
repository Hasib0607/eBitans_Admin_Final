<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('headersettings')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `headersettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` bigint(20) NOT NULL DEFAULT 1,
  `favicon` varchar(255) DEFAULT NULL,
  `logo` mediumtext DEFAULT NULL,
  `website_name` varchar(255) DEFAULT NULL,
  `short_description` mediumtext DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `map_address` longtext DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `messenger_link` varchar(255) DEFAULT NULL,
  `facebook_app_id` varchar(255) DEFAULT NULL,
  `facebook_login` varchar(255) DEFAULT NULL,
  `whatsapp_phone` varchar(255) DEFAULT NULL,
  `lined_in_link` mediumtext DEFAULT NULL,
  `pinterest_link` varchar(1000) DEFAULT NULL,
  `twitter_link` varchar(1000) DEFAULT NULL,
  `tiktok_link` varchar(1000) DEFAULT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `shipping_methods` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shipping_methods`)),
  `shipping_area_1` varchar(255) DEFAULT NULL,
  `shipping_area_1_cost` varchar(255) DEFAULT NULL,
  `shipping_area_2` varchar(255) DEFAULT NULL,
  `shipping_area_2_cost` varchar(255) DEFAULT NULL,
  `shipping_area_3` varchar(255) DEFAULT NULL,
  `shipping_area_3_cost` varchar(255) DEFAULT NULL,
  `selected_shipping_area` varchar(191) DEFAULT NULL,
  `cod` varchar(255) DEFAULT NULL,
  `online` varchar(255) DEFAULT NULL,
  `bkash` varchar(255) DEFAULT NULL,
  `nagad` varchar(255) DEFAULT NULL,
  `paypal` varchar(191) DEFAULT NULL,
  `stripe` varchar(191) DEFAULT NULL,
  `amarpay` varchar(191) DEFAULT NULL,
  `uddoktapay` varchar(191) DEFAULT NULL,
  `merchant_bkash` varchar(191) DEFAULT NULL,
  `merchant_nagad` varchar(191) DEFAULT NULL,
  `merchant_rocket` varchar(191) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `prepayment` int(11) NOT NULL DEFAULT 0,
  `payment_type` int(11) NOT NULL DEFAULT 0,
  `payment_method` varchar(191) DEFAULT NULL,
  `creator` varchar(255) DEFAULT NULL,
  `editor` varchar(255) DEFAULT NULL,
  `fb_pixel` longtext DEFAULT NULL,
  `google_analytic` longtext DEFAULT NULL,
  `expo_token` varchar(255) DEFAULT NULL,
  `pagination` int(11) NOT NULL DEFAULT 1,
  `stock_out_qty` longtext DEFAULT NULL,
  `affiliate_min_withdraw` double NOT NULL DEFAULT 0,
  `order_sms` longtext DEFAULT NULL,
  `cod_text` varchar(191) NOT NULL DEFAULT 'Cash On Delivery',
  `bkash_text` varchar(191) NOT NULL DEFAULT 'bKash Payment Img',
  `nagad_text` varchar(191) NOT NULL DEFAULT 'Nagad',
  `paypal_text` varchar(191) NOT NULL DEFAULT 'Paypal',
  `stripe_text` varchar(191) NOT NULL DEFAULT 'Stripe',
  `ap_text` varchar(191) NOT NULL DEFAULT 'Advance Payment',
  `amarpay_text` varchar(191) NOT NULL DEFAULT 'Amar Pay',
  `uddoktapay_text` varchar(191) NOT NULL DEFAULT 'Uddokta Pay',
  `merchant_bkash_text` varchar(191) NOT NULL DEFAULT 'Bkash',
  `merchant_nagad_text` varchar(191) NOT NULL DEFAULT 'Nagad',
  `merchant_rocket_text` varchar(191) NOT NULL DEFAULT 'Rocket',
  `button_status` tinyint(4) NOT NULL DEFAULT 0,
  `rtl_status` tinyint(4) NOT NULL DEFAULT 0,
  `theme_lock` tinyint(4) NOT NULL DEFAULT 0,
  `custom_writing` longtext DEFAULT NULL,
  `balance_min_withdraw` varchar(191) DEFAULT '1000',
  `balance_max_withdraw` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `headersettings_currency_id_index` (`currency_id`),
  KEY `idx_headersettings_store` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('headersettings');
    }
};