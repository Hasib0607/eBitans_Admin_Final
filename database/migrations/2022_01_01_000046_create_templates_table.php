<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('templates')) {
            return;
        }

        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('value')->nullable();
            $table->string('category')->nullable();
            $table->double('price')->nullable()->default('NULL');
            $table->string('discount_type')->nullable();
            $table->string('discount_amount')->nullable();
            $table->mediumText('feature_image')->nullable();
            $table->mediumText('main_image')->nullable();
            $table->string('mobile_image', 191)->nullable();
            $table->string('liveurl')->nullable();
            $table->mediumText('short_description')->nullable();
            $table->string('header')->nullable();
            $table->string('slider')->nullable();
            $table->string('banner')->nullable();
            $table->string('banner_bottom')->nullable();
            $table->string('feature_category')->nullable();
            $table->string('product')->nullable();
            $table->string('feature_product')->nullable();
            $table->string('best_sell_product')->nullable();
            $table->string('new_arrival')->nullable();
            $table->string('testimonial')->nullable();
            $table->string('youtube')->nullable();
            $table->string('footer')->nullable();
            $table->string('auth')->nullable();
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
            $table->string('blog', 191)->nullable();
            $table->string('contact', 191)->nullable();
            $table->string('announcement', 191)->nullable();
            $table->string('about', 191)->nullable();
            $table->string('newsletter', 191)->nullable();
            $table->string('brand', 191)->nullable();
            $table->string('is_premium')->nullable();
            $table->double('review')->default(0);
            $table->integer('reviewer')->default(0);
            $table->double('downlad')->default(0);
            $table->string('status')->nullable();
            $table->bigInteger('position')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};