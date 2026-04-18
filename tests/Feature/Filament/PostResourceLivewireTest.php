<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostResourceLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        foreach (['manage-members', 'manage-cases', 'manage-advocacy', 'manage-events', 'manage-content', 'manage-settings', 'manage-users'] as $p) {
            Permission::findOrCreate($p, 'web');
        }
        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->syncRoles(['superadmin']);

        $this->actingAs($this->admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_list_posts_page_renders(): void
    {
        Livewire::test(ListPosts::class)->assertSuccessful();
    }

    /**
     * Regresi: `/admin/posts/create` pernah HTTP 500 karena compiled view
     * di storage/framework/views dimiliki root sehingga www-data tidak bisa
     * overwrite. Test ini memastikan mount halaman CreatePost bekerja.
     */
    public function test_create_post_page_mounts_without_error(): void
    {
        Livewire::test(CreatePost::class)->assertSuccessful();
    }

    /**
     * Facet daftar artikel: filter "Profil pool AI" mempersempit post AI menurut
     * content_profile pool yang terhubung ke topik (integrasi satu resource Post).
     */
    public function test_list_posts_ai_pool_content_profile_filter(): void
    {
        $cat = Category::create(['name' => 'Kategori Uji Post', 'slug' => 'kategori-uji-post-filter']);

        $topicPillar = ArticleTopic::create([
            'title' => 'Topik Pillar Uji',
            'slug' => 'topik-pillar-uji',
            'description' => null,
            'thinking_framework' => 'marxist',
            'article_type' => 'essay',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);
        $topicPractical = ArticleTopic::create([
            'title' => 'Topik Praktis Uji',
            'slug' => 'topik-praktis-uji',
            'description' => 'Ctx',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
            'prompt_template' => '',
            'weight' => 10,
            'is_active' => true,
            'category_id' => $cat->id,
        ]);

        $poolPillar = ArticlePool::create([
            'name' => 'Pool Pillar Uji',
            'slug' => 'pool-pillar-uji',
            'schedule_frequency' => 'daily',
            'schedule_time' => '09:00',
            'schedule_times' => ['09:00'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'pillar',
        ]);
        $poolPractical = ArticlePool::create([
            'name' => 'Pool Praktis Uji',
            'slug' => 'pool-praktis-uji',
            'schedule_frequency' => 'daily',
            'schedule_time' => '10:00',
            'schedule_times' => ['10:00'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'member_practical',
        ]);
        $poolPillar->topics()->sync([$topicPillar->id]);
        $poolPractical->topics()->sync([$topicPractical->id]);

        $pillarPost = Post::create([
            'title' => 'Artikel AI Pillar',
            'slug' => 'artikel-ai-pillar',
            'body' => '<p>Isi.</p>',
            'status' => 'draft',
            'author_id' => $this->admin->id,
            'source_type' => 'auto_generated',
            'article_topic_id' => $topicPillar->id,
        ]);
        $practicalPost = Post::create([
            'title' => 'Artikel AI Praktis',
            'slug' => 'artikel-ai-praktis',
            'body' => '<p>Isi.</p>',
            'status' => 'draft',
            'author_id' => $this->admin->id,
            'source_type' => 'auto_generated',
            'article_topic_id' => $topicPractical->id,
        ]);
        $manualPost = Post::create([
            'title' => 'Artikel Manual',
            'slug' => 'artikel-manual',
            'body' => '<p>Manual.</p>',
            'status' => 'draft',
            'author_id' => $this->admin->id,
            'source_type' => 'manual',
            'article_topic_id' => null,
        ]);

        Livewire::test(ListPosts::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(Post::query()->orderBy('id')->get())
            ->filterTable('ai_pool_content_profile', 'pillar')
            ->assertCanSeeTableRecords([$pillarPost])
            ->assertCanNotSeeTableRecords([$practicalPost, $manualPost])
            ->resetTableFilters()
            ->filterTable('ai_pool_content_profile', 'member_practical')
            ->assertCanSeeTableRecords([$practicalPost])
            ->assertCanNotSeeTableRecords([$pillarPost, $manualPost]);
    }
}
