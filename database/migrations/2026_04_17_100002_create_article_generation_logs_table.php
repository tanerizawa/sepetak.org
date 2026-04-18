<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_generation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_topic_id')->nullable()->constrained('article_topics')->nullOnDelete();
            $table->foreignId('article_pool_id')->nullable()->constrained('article_pools')->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->string('status', 30)->default('queued');
            $table->string('ai_provider', 50)->nullable();
            $table->string('ai_model', 80)->nullable();
            $table->longText('prompt_used')->nullable();
            $table->longText('raw_response')->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->unsignedInteger('generation_time_ms')->nullable();
            $table->text('error_message')->nullable();
            $table->string('triggered_by', 30)->default('scheduler');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_generation_logs');
    }
};
