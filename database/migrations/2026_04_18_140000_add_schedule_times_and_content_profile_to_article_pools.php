<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_pools', function (Blueprint $table) {
            $table->json('schedule_times')->nullable()->after('schedule_time');
            $table->string('content_profile', 32)->default('pillar')->after('auto_publish');
        });
    }

    public function down(): void
    {
        Schema::table('article_pools', function (Blueprint $table) {
            $table->dropColumn(['schedule_times', 'content_profile']);
        });
    }
};
