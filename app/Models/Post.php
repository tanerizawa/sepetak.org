<?php

namespace App\Models;

use App\Support\PostBodyHtml;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'status',
        'published_at',
        'author_id',
        'source_type',
        'article_topic_id',
        'generation_log_id',
        'ai_disclosure',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'ai_disclosure' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function articleTopic(): BelongsTo
    {
        return $this->belongsTo(ArticleTopic::class, 'article_topic_id');
    }

    public function generationLog(): BelongsTo
    {
        return $this->belongsTo(ArticleGenerationLog::class, 'generation_log_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    /**
     * @return array{html: string, toc: list<array{id: string, text: string, level: 2|3|4}>}
     */
    public function articlePresentationForPublic(): array
    {
        return PostBodyHtml::articlePresentation((string) $this->body, (string) $this->title);
    }

    /** HTML isi siap tampil publik (tanpa judul ganda, anchor heading). */
    public function bodyForPublicDisplay(): string
    {
        return $this->articlePresentationForPublic()['html'];
    }

    public function readingTimeMinutes(): int
    {
        $html = (string) ($this->body ?? '');
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags($html)) ?? '');
        if ($text === '') {
            return 1;
        }

        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $count = count($words);
        $minutes = (int) ceil($count / 200);

        return max(1, $minutes);
    }
}
