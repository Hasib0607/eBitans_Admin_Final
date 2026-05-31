<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stores')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `category_id` varchar(191) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `store_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive|1=Active',
  `paid_registration` tinyint(4) NOT NULL DEFAULT 0,
  `plan_id` varchar(255) DEFAULT NULL,
  `template_id` varchar(255) DEFAULT NULL,
  `currency` int(11) NOT NULL DEFAULT 1,
  `currency_rate` decimal(10,4) NOT NULL DEFAULT 117.5600,
  `bkash` varchar(255) DEFAULT 'no',
  `purchase_date` date DEFAULT NULL,
  `renew_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `dropship_commission` int(10) unsigned NOT NULL DEFAULT 3,
  `order_pull` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=order place|1=order delivered',
  `overflow_commission` decimal(8,2) NOT NULL DEFAULT 10000.00,
  `pos_plan_id` varchar(255) DEFAULT NULL,
  `pos_plan_start_date` date DEFAULT NULL,
  `pos_plan_expiry_date` date DEFAULT NULL,
  `pos_plan_month` bigint(20) DEFAULT 0,
  `pos_plan_status` varchar(255) DEFAULT NULL,
  `digital_plan_id` varchar(255) DEFAULT NULL,
  `digital_plan_start_date` date DEFAULT NULL,
  `digital_plan_end_date` date DEFAULT NULL,
  `digital_plan_status` varchar(255) DEFAULT NULL,
  `plan_status` varchar(255) DEFAULT NULL,
  `trail` int(11) NOT NULL DEFAULT 0,
  `month` bigint(20) DEFAULT NULL,
  `upcoming_plan_id` varchar(255) DEFAULT NULL,
  `upcoming_plan_month` varchar(255) DEFAULT NULL,
  `upcoming_plan_purchase_date` timestamp NULL DEFAULT NULL,
  `upcoming_plan_expiry_date` timestamp NULL DEFAULT NULL,
  `upcoming_pos_plan_id` varchar(255) DEFAULT NULL,
  `upcoming_pos_plan_month` varchar(255) DEFAULT NULL,
  `upcoming_pos_plan_start_date` date DEFAULT NULL,
  `upcoming_pos_plan_expiry_date` date DEFAULT NULL,
  `upcoming_digital_plan_id` varchar(255) DEFAULT NULL,
  `upcoming_digital_plan_month` varchar(255) DEFAULT NULL,
  `upcoming_digital_plan_start_date` date DEFAULT NULL,
  `upcoming_digital_plan_expiry_date` date DEFAULT NULL,
  `webmail_status` varchar(255) DEFAULT NULL,
  `sms_plan` int(11) DEFAULT 0,
  `auth_type` varchar(255) NOT NULL DEFAULT 'EmailEasyOrder',
  `pay_noti` int(11) NOT NULL DEFAULT 1,
  `call_status` tinyint(4) NOT NULL DEFAULT 0,
  `sms_status` varchar(191) NOT NULL DEFAULT '0',
  `pay_mail_status` tinyint(4) NOT NULL DEFAULT 0,
  `setup_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Not Buy|1=Buy',
  `isDomainDelete` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=Not Delete|1=Delete',
  `isCFileDelete` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=Not Delete|1=Delete',
  `analytic_email` varchar(191) DEFAULT NULL,
  `bkash_token` longtext NOT NULL,
  `alert_popup` varchar(191) DEFAULT NULL,
  `access_key` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `access_key` (`access_key`),
  KEY `stores_user_id_index` (`user_id`),
  KEY `stores_name_index` (`name`),
  KEY `stores_url_index` (`url`),
  KEY `idx_expiry` (`expiry_date`),
  KEY `idx_stores_url_expiry` (`url`,`expiry_date`)
) ENGINE=InnoDB AUTO_INCREMENT=15138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};