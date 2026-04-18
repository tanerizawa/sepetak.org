<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('source_type', 20)->default('manual')->after('author_id');
            $table->foreignId('article_topic_id')->nullable()->after('source_type')
                ->constrained('article_topics')->nullOnDelete();
            $table->foreignId('generation_log_id')->nullable()->after('article_topic_id')
                ->constrained('article_generation_logs')->nullOnDelete();
            $table->boolean('ai_disclosure')->default(false)->after('generation_log_id');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('article_topic_id');
            $table->dropConstrainedForeignId('generation_log_id');
            $table->dropColumn(['source_type', 'ai_disclosure']);
        });
    }
};
