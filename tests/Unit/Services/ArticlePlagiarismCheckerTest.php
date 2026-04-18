<?php

namespace Tests\Unit\Services;

use App\Models\Post;
use App\Services\ArticlePlagiarismChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlePlagiarismCheckerTest extends TestCase
{
    use RefreshDatabase;

    public function test_detects_high_similarity_against_existing_posts(): void
    {
        config([
            'article-generator.quality.plagiarism.enabled' => true,
            'article-generator.quality.plagiarism.max_similarity' => 0.3,
            'article-generator.quality.plagiarism.candidate_limit' => 50,
            'article-generator.quality.plagiarism.lookback_days' => 3650,
        ]);

        $text = implode(' ', array_fill(0, 120, 'Serikat pekerja tani memperjuangkan reforma agraria dan keadilan sosial'));

        $p = Post::query()->create([
            'title' => 'Post Lama',
            'slug' => 'post-lama',
            'body' => '<p>'.$text.'</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $checker = app(ArticlePlagiarismChecker::class);
        $result = $checker->checkMarkdown("# Judul\n\n{$text}\n\n## Penutup\n\n{$text}");

        $this->assertNotNull($result->matchedPostId);
        $this->assertSame($p->id, $result->matchedPostId);
        $this->assertGreaterThanOrEqual(0.3, $result->maxSimilarity);
    }

    public function test_returns_zero_when_disabled(): void
    {
        config(['article-generator.quality.plagiarism.enabled' => false]);

        $checker = app(ArticlePlagiarismChecker::class);
        $result = $checker->checkMarkdown("# Judul\n\nHalo dunia.\n");

        $this->assertSame(0.0, $result->maxSimilarity);
        $this->assertNull($result->matchedPostId);
    }
}

