<?php

namespace Tests\Feature\ArticleGenerator;

use App\Contracts\ArticleAiProvider;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\Support\FakeArticleAiProvider;
use Tests\TestCase;

class ArticlesGenerateCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function seedRolesAndAdmin(): User
    {
        foreach (['superadmin', 'admin'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
        foreach (['manage-members', 'manage-cases', 'manage-content', 'manage-users', 'manage-settings', 'manage-advocacy', 'manage-events'] as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }
        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());

        $user = User::factory()->create(['is_active' => true]);
        $user->syncRoles(['superadmin']);

        return $user;
    }

    public function test_sync_generates_published_post_for_due_member_pool(): void
    {
        Carbon::setTestNow(Carbon::parse('2030-05-10 04:45:00', 'Asia/Jakarta'));

        $this->seedRolesAndAdmin();

        config([
            'article-generator.enabled' => true,
            'article-generator.schedule_timezone' => 'Asia/Jakarta',
            'article-generator.unsplash.enabled' => false,
            'article-generator.defaults.author_user_id' => User::query()->first()->id,
        ]);

        $this->app->instance(ArticleAiProvider::class, new FakeArticleAiProvider);

        $cat = Category::create(['name' => 'Panduan & Tips Anggota', 'slug' => 'panduan-tips-anggota']);

        $topic = ArticleTopic::create([
            'title' => 'Topik Integrasi Perintah',
            'slug' => 'topik-integrasi-perintah',
            'description' => 'Deskripsi integrasi.',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
            'prompt_template' => '',
            'weight' => 55,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);

        $pool = ArticlePool::create([
            'name' => 'Pool Integrasi',
            'slug' => 'pool-integrasi-cmd',
            'schedule_frequency' => 'daily',
            'schedule_time' => '04:45',
            'schedule_times' => ['04:45'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => true,
            'content_profile' => 'member_practical',
        ]);
        $pool->topics()->sync([$topic->id]);

        $exit = Artisan::call('articles:generate', ['--sync' => true]);

        $this->assertSame(0, $exit);
        $this->assertSame(1, Post::query()->count());
        $post = Post::query()->first();
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertSame('auto_generated', $post->source_type);
        $this->assertSame($topic->id, $post->article_topic_id);

        Carbon::setTestNow();
    }

    public function test_command_respects_daily_cap(): void
    {
        Carbon::setTestNow(Carbon::parse('2030-05-10 12:10:00', 'Asia/Jakarta'));

        $this->seedRolesAndAdmin();

        config([
            'article-generator.enabled' => true,
            'article-generator.schedule_timezone' => 'Asia/Jakarta',
            'article-generator.limits.max_per_day' => 0,
            'article-generator.unsplash.enabled' => false,
            'article-generator.defaults.author_user_id' => User::query()->first()->id,
        ]);

        $this->app->instance(ArticleAiProvider::class, new FakeArticleAiProvider);

        $pool = ArticlePool::create([
            'name' => 'Pool Cap',
            'slug' => 'pool-cap',
            'schedule_frequency' => 'daily',
            'schedule_time' => '12:10',
            'schedule_times' => ['12:10'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => true,
            'content_profile' => 'member_practical',
        ]);

        $exit = Artisan::call('articles:generate', ['--sync' => true]);

        $this->assertSame(0, $exit);
        $this->assertSame(0, Post::query()->count());

        Carbon::setTestNow();
    }
}
