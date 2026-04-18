<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ArticleTopic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thinking_framework',
        'article_type',
        'key_references',
        'prompt_template',
        'weight',
        'max_uses',
        'times_used',
        'is_active',
        'category_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'key_references' => 'array',
            'weight' => 'integer',
            'max_uses' => 'integer',
            'times_used' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $topic) {
            if (empty($topic->slug)) {
                $topic->slug = Str::slug($topic->title);
            }
        });
    }

    public function isAvailable(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->max_uses !== null && $this->times_used >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_topic_tags');
    }

    public function pools(): BelongsToMany
    {
        return $this->belongsToMany(ArticlePool::class, 'article_pool_topic');
    }

    public function generationLogs(): HasMany
    {
        return $this->hasMany(ArticleGenerationLog::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'article_topic_id');
    }
}
