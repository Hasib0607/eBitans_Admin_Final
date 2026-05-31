<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_customer_fields')) {
            return;
        }

        DB::statement(<<<'SQL'
CREATE TABLE `booking_customer_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulus_id` text NOT NULL,
  `name` text NOT NULL,
  `tagId` int(11) NOT NULL,
  `is_required` text NOT NULL,
  `store_id` text NOT NULL,
  `customer_id` text NOT NULL,
  `is_checked` int(11) NOT NULL,
  `uId` text NOT NULL,
  `is_single` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_customer_fields');
    }
};