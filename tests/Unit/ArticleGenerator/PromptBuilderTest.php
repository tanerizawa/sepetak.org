<?php

namespace Tests\Unit\ArticleGenerator;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\PromptBuilder;
use Tests\TestCase;

class PromptBuilderTest extends TestCase
{
    public function test_system_prompt_switches_for_member_practical_pool(): void
    {
        $builder = PromptBuilder::makeDefault();
        $pillar = $builder->getSystemPrompt(null);
        $practical = $builder->getSystemPrompt(new ArticlePool(['content_profile' => 'member_practical']));

        $this->assertStringContainsString('ilmiah populer', $pillar);
        $this->assertStringNotContainsString('550–1.000 kata', $pillar);
        $this->assertStringContainsString('550–1.000 kata', $practical);
        $this->assertStringContainsString('ringan dan praktis', $practical);
    }

    public function test_user_prompt_lists_recent_titles_for_member_practical(): void
    {
        $builder = PromptBuilder::makeDefault();
        $topic = new ArticleTopic([
            'title' => 'Topik Uji',
            'description' => 'Deskripsi uji',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
        ]);

        $pool = new ArticlePool(['content_profile' => 'member_practical']);
        $prompt = $builder->buildUserPrompt($topic, $pool, ['Judul Lama A', 'Judul Lama B']);

        $this->assertStringContainsString('Topik Uji', $prompt);
        $this->assertStringContainsString('Judul Lama A', $prompt);
        $this->assertStringContainsString('artikel otomatis yang baru-baru ini terbit', $prompt);
        $this->assertStringContainsString('materi praktis', strtolower($prompt));
    }

    public function test_user_prompt_omits_recent_block_for_pillar_pool(): void
    {
        $builder = PromptBuilder::makeDefault();
        $topic = new ArticleTopic([
            'title' => 'Topik Pillar',
            'description' => null,
            'thinking_framework' => 'marxist',
            'article_type' => 'essay',
        ]);
        $pool = new ArticlePool(['content_profile' => 'pillar']);
        $prompt = $builder->buildUserPrompt($topic, $pool, ['Sebuah Judul']);

        $this->assertStringNotContainsString('Judul artikel otomatis yang baru-baru ini terbit', $prompt);
    }
}
