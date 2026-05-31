<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('headersettings')) {
            return;
        }

        Schema::create('headersettings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('currency_id')->default(1);
            $table->string('favicon')->nullable();
            $table->mediumText('logo')->nullable();
            $table->string('website_name')->nullable();
            $table->mediumText('short_description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->longText('map_address')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('messenger_link')->nullable();
            $table->string('facebook_app_id')->nullable();
            $table->string('facebook_login')->nullable();
            $table->string('whatsapp_phone')->nullable();
            $table->mediumText('lined_in_link')->nullable();
            $table->string('pinterest_link', 1000)->nullable();
            $table->string('twitter_link', 1000)->nullable();
            $table->string('tiktok_link', 1000)->nullable();
            $table->string('tax')->nullable();
            $table->longText('shipping_methods')->nullable();
            $table->string('shipping_area_1')->nullable();
            $table->string('shipping_area_1_cost')->nullable();
            $table->string('shipping_area_2')->nullable();
            $table->string('shipping_area_2_cost')->nullable();
            $table->string('shipping_area_3')->nullable();
            $table->string('shipping_area_3_cost')->nullable();
            $table->string('selected_shipping_area', 191)->nullable();
            $table->string('cod')->nullable();
            $table->string('online')->nullable();
            $table->string('bkash')->nullable();
            $table->string('nagad')->nullable();
            $table->string('paypal', 191)->nullable();
            $table->string('stripe', 191)->nullable();
            $table->string('amarpay', 191)->nullable();
            $table->string('uddoktapay', 191)->nullable();
            $table->string('merchant_bkash', 191)->nullable();
            $table->string('merchant_nagad', 191)->nullable();
            $table->string('merchant_rocket', 191)->nullable();
            $table->string('uid')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('store_id')->nullable();
            $table->integer('prepayment')->default(0);
            $table->integer('payment_type')->default(0);
            $table->string('payment_method', 191)->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->longText('fb_pixel')->nullable();
            $table->longText('google_analytic')->nullable();
            $table->string('expo_token')->nullable();
            $table->integer('pagination')->default(1);
            $table->longText('stock_out_qty')->nullable();
            $table->double('affiliate_min_withdraw')->default(0);
            $table->longText('order_sms')->nullable();
            $table->string('cod_text', 191)->default('Cash On Delivery');
            $table->string('bkash_text', 191)->default('bKash Payment Img');
            $table->string('nagad_text', 191)->default('Nagad');
            $table->string('paypal_text', 191)->default('Paypal');
            $table->string('stripe_text', 191)->default('Stripe');
            $table->string('ap_text', 191)->default('Advance Payment');
            $table->string('amarpay_text', 191)->default('Amar Pay');
            $table->string('uddoktapay_text', 191)->default('Uddokta Pay');
            $table->string('merchant_bkash_text', 191)->default('Bkash');
            $table->string('merchant_nagad_text', 191)->default('Nagad');
            $table->string('merchant_rocket_text', 191)->default('Rocket');
            $table->tinyInteger('button_status')->default(0);
            $table->tinyInteger('rtl_status')->default(0);
            $table->tinyInteger('theme_lock')->default(0);
            $table->longText('custom_writing')->nullable();
            $table->string('balance_min_withdraw', 191)->nullable()->default('1000');
            $table->string('balance_max_withdraw', 191)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index('currency_id', 'headersettings_currency_id_index');
            $table->index('store_id', 'idx_headersettings_store');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('headersettings');
    }
};