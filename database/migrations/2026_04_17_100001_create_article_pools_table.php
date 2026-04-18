<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_pools', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('schedule_frequency', 30)->default('weekly');
            $table->string('schedule_day', 10)->nullable();
            $table->time('schedule_time')->default('07:00');
            $table->unsignedSmallInteger('articles_per_run')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_publish')->default(false);
            $table->timestamps();
        });

        Schema::create('article_pool_topic', function (Blueprint $table) {
            $table->foreignId('article_pool_id')->constrained('article_pools')->cascadeOnDelete();
            $table->foreignId('article_topic_id')->constrained('article_topics')->cascadeOnDelete();
            $table->primary(['article_pool_id', 'article_topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_pool_topic');
        Schema::dropIfExists('article_pools');
    }
};
