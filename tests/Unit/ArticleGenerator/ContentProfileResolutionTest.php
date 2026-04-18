<?php

namespace Tests\Unit\ArticleGenerator;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Services\ArticleGeneration\AcademicPromptStrategy;
use App\Services\ArticleGeneration\ContentProfile;
use App\Services\ArticleGeneration\MemberPracticalPromptStrategy;
use App\Services\ArticleGeneration\PromptComposer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentProfileResolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_null_pool_resolves_member_practical_from_topic_pools(): void
    {
        $cat = Category::create(['name' => 'Cat Prof', 'slug' => 'cat-prof']);
        $topic = ArticleTopic::create([
            'title' => 'Topik Hukum Ringkas',
            'slug' => 'topik-hukum-ringkas',
            'description' => 'Ctx',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);
        $pool = ArticlePool::create([
            'name' => 'Pool Tips',
            'slug' => 'pool-tips-prof',
            'schedule_frequency' => 'daily',
            'schedule_time' => '09:00',
            'schedule_times' => ['09:00'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'member_practical',
        ]);
        $pool->topics()->sync([$topic->id]);

        $this->assertTrue(ContentProfile::forArticleGeneration(null, $topic)->isMemberPractical());

        $composer = new PromptComposer(
            new AcademicPromptStrategy,
            new MemberPracticalPromptStrategy,
        );
        $user = $composer->buildUserPrompt(null, $topic, []);

        $this->assertStringContainsString('JALUR MATERI PRAKTIS', $user);
        $this->assertStringNotContainsString('JALUR ARTIKEL ILMIAH', $user);
        $this->assertStringContainsString('struktur **praktis**', $user);

        $system = $composer->systemPrompt(null, $topic);
        $this->assertStringContainsString('550–1.000 kata', $system);
    }

    public function test_null_pool_pillar_topic_stays_academic(): void
    {
        $cat = Category::create(['name' => 'Cat Pillar', 'slug' => 'cat-pillar']);
        $topic = ArticleTopic::create([
            'title' => 'Topik Kajian',
            'slug' => 'topik-kajian',
            'description' => null,
            'thinking_framework' => 'marxist',
            'article_type' => 'essay',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);
        $pool = ArticlePool::create([
            'name' => 'Pool Pillar',
            'slug' => 'pool-pillar-prof',
            'schedule_frequency' => 'daily',
            'schedule_time' => '10:00',
            'schedule_times' => ['10:00'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'pillar',
        ]);
        $pool->topics()->sync([$topic->id]);

        $this->assertFalse(ContentProfile::forArticleGeneration(null, $topic)->isMemberPractical());

        $composer = new PromptComposer(
            new AcademicPromptStrategy,
            new MemberPracticalPromptStrategy,
        );
        $user = $composer->buildUserPrompt(null, $topic, []);

        $this->assertStringContainsString('JALUR ARTIKEL ILMIAH', $user);
        $this->assertStringNotContainsString('JALUR MATERI PRAKTIS', $user);
    }

    public function test_null_pool_member_guide_without_pools_is_practical(): void
    {
        $cat = Category::create(['name' => 'Kategori Lain', 'slug' => 'kategori-lain-inf']);
        $topic = ArticleTopic::create([
            'title' => 'Tips Bukti Foto',
            'slug' => 'tips-bukti-foto',
            'description' => 'Ctx',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);

        $this->assertTrue(ContentProfile::forArticleGeneration(null, $topic)->isMemberPractical());

        $composer = new PromptComposer(
            new AcademicPromptStrategy,
            new MemberPracticalPromptStrategy,
        );
        $this->assertStringContainsString(
            'JALUR MATERI PRAKTIS',
            $composer->buildUserPrompt(null, $topic, [])
        );
    }

    public function test_null_pool_essay_in_tips_category_is_practical(): void
    {
        $cat = Category::create(['name' => 'Panduan Anggota', 'slug' => 'panduan-tips-anggota']);
        $topic = ArticleTopic::create([
            'title' => 'Topik Kategori Tips',
            'slug' => 'topik-kategori-tips',
            'description' => null,
            'thinking_framework' => 'human_rights',
            'article_type' => 'essay',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);

        $this->assertTrue(ContentProfile::forArticleGeneration(null, $topic)->isMemberPractical());
    }

    /**
     * When pool is explicitly provided, pool profile wins over topic signals.
     * This ensures explicit user/UI pool selection is respected.
     * The dispatch-from-Filament bug (sending wrong pool) is a separate issue.
     */
    public function test_explicit_pillar_pool_with_member_guide_topic_is_pillar(): void
    {
        $cat = Category::create(['name' => 'Lain', 'slug' => 'lain-pillar-mix']);
        $topic = ArticleTopic::create([
            'title' => 'Checklist Aksi Damai',
            'slug' => 'checklist-aksi-damai',
            'description' => 'Koordinasi lini depan.',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);
        $pillarPool = ArticlePool::create([
            'name' => 'Pool Pillar Campur',
            'slug' => 'pool-pillar-campur',
            'schedule_frequency' => 'daily',
            'schedule_time' => '08:00',
            'schedule_times' => ['08:00'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'pillar',
        ]);
        $pillarPool->topics()->sync([$topic->id]);

        $this->assertFalse(ContentProfile::forArticleGeneration($pillarPool, $topic)->isMemberPractical());

        $composer = new PromptComposer(
            new AcademicPromptStrategy,
            new MemberPracticalPromptStrategy,
        );
        $user = $composer->buildUserPrompt($pillarPool, $topic, []);
        $this->assertStringContainsString('JALUR ARTIKEL ILMIAH', $user);
        $this->assertStringNotContainsString('JALUR MATERI PRAKTIS', $user);
    }

    public function test_explicit_pillar_pool_essay_topic_stays_pillar(): void
    {
        $cat = Category::create(['name' => 'Opini', 'slug' => 'opini-khusus']);
        $topic = ArticleTopic::create([
            'title' => 'Esai Kebijakan',
            'slug' => 'esai-kebijakan-tes',
            'description' => null,
            'thinking_framework' => 'marxist',
            'article_type' => 'essay',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);
        $pillarPool = ArticlePool::create([
            'name' => 'Pool Pillar Murni',
            'slug' => 'pool-pillar-murni',
            'schedule_frequency' => 'daily',
            'schedule_time' => '07:00',
            'schedule_times' => ['07:00'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'pillar',
        ]);
        $pillarPool->topics()->sync([$topic->id]);

        $this->assertFalse(ContentProfile::forArticleGeneration($pillarPool, $topic)->isMemberPractical());
    }
}
