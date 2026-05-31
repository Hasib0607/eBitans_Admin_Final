<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('designs')) {
            return;
        }

        Schema::create('designs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('header')->nullable();
            $table->string('header_color')->nullable();
            $table->string('text_color')->nullable();
            $table->longText('section_settings')->nullable();
            $table->string('hero_slider')->nullable();
            $table->string('banner')->nullable();
            $table->string('banner_bottom')->nullable();
            $table->string('feature_category')->nullable();
            $table->string('product')->nullable();
            $table->string('feature_product')->nullable();
            $table->string('best_sell_product')->nullable();
            $table->string('new_arrival')->nullable();
            $table->string('testimonial')->nullable();
            $table->string('youtube', 191)->nullable()->default('none');
            $table->string('announcement', 191)->nullable();
            $table->string('about', 191)->nullable();
            $table->string('newsletter', 191)->nullable();
            $table->string('brand', 191)->nullable()->default('none');
            $table->string('footer')->nullable();
            $table->string('auth')->nullable()->default('one');
            $table->string('single_product_page')->nullable()->default('default');
            $table->string('shop_page')->nullable()->default('default');
            $table->string('checkout_page')->nullable()->default('default');
            $table->string('login_page')->nullable()->default('default');
            $table->string('profile_page')->nullable()->default('default');
            $table->string('invoice')->nullable()->default('default');
            $table->string('product_card')->nullable()->default('default');
            $table->string('product_modal')->nullable()->default('default');
            $table->string('preloader')->nullable()->default('default');
            $table->string('mobile_bottom_menu')->nullable()->default('default');
            $table->string('offer')->nullable()->default('default');
            $table->string('blog', 50);
            $table->string('contact', 50);
            $table->string('template_id')->nullable()->default('0');
            $table->string('uid')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('store_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index('store_id', 'idx_designs_store');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};