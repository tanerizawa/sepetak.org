<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleGenerationLog extends Model
{
    protected $fillable = [
        'article_topic_id',
        'article_pool_id',
        'content_profile',
        'post_id',
        'status',
        'ai_provider',
        'ai_model',
        'prompt_variant',
        'prompt_used',
        'raw_response',
        'tokens_used',
        'generation_time_ms',
        'word_count',
        'readability_score',
        'plagiarism_score',
        'plagiarism_matched_post_id',
        'error_message',
        'triggered_by',
    ];

    protected function casts(): array
    {
        return [
            'tokens_used' => 'integer',
            'generation_time_ms' => 'integer',
            'word_count' => 'integer',
            'readability_score' => 'integer',
            'plagiarism_score' => 'float',
            'plagiarism_matched_post_id' => 'integer',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ArticleTopic::class, 'article_topic_id');
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(ArticlePool::class, 'article_pool_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function markGenerating(): void
    {
        $this->update(['status' => 'generating']);
    }

    public function markCompleted(Post $post, int $tokens, int $timeMs): void
    {
        $this->update([
            'status' => 'completed',
            'post_id' => $post->id,
            'tokens_used' => $tokens,
            'generation_time_ms' => $timeMs,
        ]);
    }

    public function markFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }
}
