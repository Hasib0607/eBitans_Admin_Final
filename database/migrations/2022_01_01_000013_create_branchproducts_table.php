<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('branchproducts')) {
            return;
        }

        Schema::create('branchproducts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('uid')->nullable();
            $table->string('creator')->nullable();
            $table->string('editor')->nullable();
            $table->string('customer_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branchproducts');
    }
};