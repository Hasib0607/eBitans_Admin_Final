<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_client_showcases', function (Blueprint $table) {
            if (!Schema::hasColumn('live_client_showcases', 'video_url')) {
                $table->text('video_url')->nullable()->after('url');
            }
            if (!Schema::hasColumn('live_client_showcases', 'video_title')) {
                $table->string('video_title')->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('live_client_showcases', 'content_type')) {
                $table->string('content_type', 50)->default('live_client')->after('video_title');
            }
            if (!Schema::hasColumn('live_client_showcases', 'feature_tag')) {
                $table->string('feature_tag', 100)->nullable()->after('content_type');
            }
            if (!Schema::hasColumn('live_client_showcases', 'objection_type')) {
                $table->string('objection_type', 100)->nullable()->after('feature_tag');
            }
            if (!Schema::hasColumn('live_client_showcases', 'business_type')) {
                $table->string('business_type', 100)->nullable()->after('objection_type');
            }
            if (!Schema::hasColumn('live_client_showcases', 'description')) {
                $table->text('description')->nullable()->after('business_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('live_client_showcases', function (Blueprint $table) {
            foreach ([
                'video_url',
                'video_title',
                'content_type',
                'feature_tag',
                'objection_type',
                'business_type',
                'description',
            ] as $column) {
                if (Schema::hasColumn('live_client_showcases', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
