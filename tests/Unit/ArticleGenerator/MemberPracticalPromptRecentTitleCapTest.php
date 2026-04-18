<?php

namespace Tests\Unit\ArticleGenerator;

use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\MemberPracticalPromptStrategy;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MemberPracticalPromptRecentTitleCapTest extends TestCase
{
    public function test_recent_title_list_respects_configured_max(): void
    {
        Config::set('article-generator.member_practical_prompt.recent_title_max', 3);

        $strategy = new MemberPracticalPromptStrategy;
        $topic = new ArticleTopic([
            'title' => 'Topik uji',
            'description' => null,
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
        ]);

        $titles = array_map(fn (int $i) => "Judul otomatis ke-{$i}", range(1, 30));
        $prompt = $strategy->buildUserPrompt($topic, $titles);

        $this->assertStringContainsString('Judul otomatis ke-1', $prompt);
        $this->assertStringContainsString('Judul otomatis ke-3', $prompt);
        $this->assertStringNotContainsString('Judul otomatis ke-4', $prompt);
        $this->assertStringContainsString('sampai 3 judul terbaru', $prompt);
    }
}
