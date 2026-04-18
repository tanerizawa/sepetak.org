<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_generation_logs', function (Blueprint $table) {
            $table->string('content_profile', 30)->nullable()->after('article_pool_id');
            $table->string('prompt_variant', 40)->nullable()->after('ai_model');
            $table->unsignedInteger('word_count')->nullable()->after('generation_time_ms');
            $table->unsignedSmallInteger('readability_score')->nullable()->after('word_count');
            $table->decimal('plagiarism_score', 6, 5)->nullable()->after('readability_score');
            $table->foreignId('plagiarism_matched_post_id')->nullable()->after('plagiarism_score')->constrained('posts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('article_generation_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plagiarism_matched_post_id');
            $table->dropColumn([
                'content_profile',
                'prompt_variant',
                'word_count',
                'readability_score',
                'plagiarism_score',
            ]);
        });
    }
};

