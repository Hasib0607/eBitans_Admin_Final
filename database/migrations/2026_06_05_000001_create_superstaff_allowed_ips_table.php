<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('superstaff_allowed_ips')) {
            Schema::create('superstaff_allowed_ips', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('ip_address', 45)->unique();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }

        $envIps = array_filter(array_map('trim', explode(',', (string) env('SUPERSTAFF_WHITELISTED_IPS', ''))));

        foreach (array_values(array_unique($envIps)) as $index => $ipAddress) {
            DB::table('superstaff_allowed_ips')->updateOrInsert(
                ['ip_address' => $ipAddress],
                [
                    'name' => 'Imported IP ' . ($index + 1),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('superstaff_allowed_ips');
    }
};
