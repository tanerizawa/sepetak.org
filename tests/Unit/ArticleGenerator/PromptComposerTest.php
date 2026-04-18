<?php

namespace Tests\Unit\ArticleGenerator;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\AcademicPromptStrategy;
use App\Services\ArticleGeneration\MemberPracticalPromptStrategy;
use App\Services\ArticleGeneration\PromptComposer;
use Tests\TestCase;

/**
 * Memastikan composer memilih strategi terpisah (ilmiah vs praktis) tanpa mencampur instruksi user prompt.
 */
class PromptComposerTest extends TestCase
{
    private function composer(): PromptComposer
    {
        return new PromptComposer(
            new AcademicPromptStrategy,
            new MemberPracticalPromptStrategy,
        );
    }

    private function sampleTopic(): ArticleTopic
    {
        return new ArticleTopic([
            'title' => 'Topik Uji Composer',
            'description' => 'Deskripsi',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
        ]);
    }

    public function test_system_prompt_is_academic_for_null_pool(): void
    {
        $system = $this->composer()->systemPrompt(null);

        $this->assertStringContainsString('ilmiah populer', $system);
        $this->assertStringNotContainsString('550–1.000 kata', $system);
    }

    public function test_system_prompt_is_practical_for_member_practical_pool(): void
    {
        $pool = new ArticlePool(['content_profile' => 'member_practical']);
        $system = $this->composer()->systemPrompt($pool);

        $this->assertStringContainsString('ringan dan praktis', $system);
        $this->assertStringContainsString('550–1.000 kata', $system);
    }

    public function test_user_prompt_pillar_branch_excludes_recent_title_block(): void
    {
        $topic = $this->sampleTopic();
        $pool = new ArticlePool(['content_profile' => 'pillar']);
        $prompt = $this->composer()->buildUserPrompt($pool, $topic, ['Judul Unik XYZ Recent']);

        $this->assertStringContainsString('JALUR ARTIKEL ILMIAH', $prompt);
        $this->assertStringNotContainsString('JALUR MATERI PRAKTIS', $prompt);
        $this->assertStringNotContainsString('Judul Unik XYZ Recent', $prompt);
        $this->assertStringNotContainsString('Judul artikel otomatis yang baru-baru ini terbit', $prompt);
    }

    public function test_user_prompt_practical_branch_includes_recent_titles(): void
    {
        $topic = $this->sampleTopic();
        $pool = new ArticlePool(['content_profile' => 'member_practical']);
        $prompt = $this->composer()->buildUserPrompt($pool, $topic, ['Judul Unik XYZ Recent']);

        $this->assertStringContainsString('JALUR MATERI PRAKTIS', $prompt);
        $this->assertStringNotContainsString('JALUR ARTIKEL ILMIAH', $prompt);
        $this->assertStringContainsString('Judul Unik XYZ Recent', $prompt);
        $this->assertStringContainsString('Judul artikel otomatis yang baru-baru ini terbit', $prompt);
    }
}
