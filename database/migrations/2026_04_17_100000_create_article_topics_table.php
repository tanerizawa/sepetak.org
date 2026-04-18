<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('thinking_framework', 100)->default('marxist');
            $table->string('article_type', 50)->default('essay');
            $table->json('key_references')->nullable();
            $table->longText('prompt_template');
            $table->unsignedInteger('weight')->default(50);
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('times_used')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('article_topic_tags', function (Blueprint $table) {
            $table->foreignId('article_topic_id')->constrained('article_topics')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['article_topic_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_topic_tags');
        Schema::dropIfExists('article_topics');
    }
};
