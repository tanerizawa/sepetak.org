<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticlePool extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'schedule_frequency',
        'schedule_day',
        'schedule_time',
        'schedule_times',
        'content_profile',
        'articles_per_run',
        'is_active',
        'auto_publish',
    ];

    protected function casts(): array
    {
        return [
            'schedule_times' => 'array',
            'articles_per_run' => 'integer',
            'is_active' => 'boolean',
            'auto_publish' => 'boolean',
        ];
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(ArticleTopic::class, 'article_pool_topic');
    }

    public function generationLogs(): HasMany
    {
        return $this->hasMany(ArticleGenerationLog::class, 'article_pool_id');
    }
}
