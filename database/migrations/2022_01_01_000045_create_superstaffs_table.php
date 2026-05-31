<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('superstaffs')) {
            return;
        }

        Schema::create('superstaffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('password')->nullable();
            $table->bigInteger('role_id')->nullable()->default('NULL');
            $table->bigInteger('uid')->nullable()->default('NULL');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('active_store')->nullable()->default('NULL');
            $table->decimal('new_commission', 8, 2)->default(10.0);
            $table->decimal('renew_commission', 8, 2)->default(5.0);
            $table->decimal('setup_commission', 8, 2)->default(5.0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('superstaffs');
    }
};