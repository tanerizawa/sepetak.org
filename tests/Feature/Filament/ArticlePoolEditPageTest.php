<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ArticlePoolResource\Pages\EditArticlePool;
use App\Models\ArticlePool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ArticlePoolEditPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('superadmin', 'web');
        Permission::findOrCreate('manage-content', 'web');
    }

    public function test_edit_article_pool_mounts_with_schedule_times_array(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('superadmin');
        $user->givePermissionTo('manage-content');

        $pool = ArticlePool::create([
            'name' => 'Pool Uji',
            'slug' => 'pool-uji-slug-stabil',
            'description' => null,
            'schedule_frequency' => 'daily',
            'schedule_day' => null,
            'schedule_time' => '07:00',
            'schedule_times' => ['04:45', '12:10'],
            'content_profile' => 'member_practical',
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
        ]);

        Livewire::actingAs($user)
            ->test(EditArticlePool::class, ['record' => $pool->getKey()])
            ->assertSuccessful()
            ->assertFormSet([
                'slug' => 'pool-uji-slug-stabil',
                'schedule_times' => ['04:45', '12:10'],
            ]);
    }

    public function test_name_blur_does_not_overwrite_existing_slug(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('superadmin');
        $user->givePermissionTo('manage-content');

        $pool = ArticlePool::create([
            'name' => 'Nama Panjang',
            'slug' => 'slug-tetap',
            'description' => null,
            'schedule_frequency' => 'daily',
            'schedule_day' => null,
            'schedule_time' => '07:00',
            'schedule_times' => [],
            'content_profile' => 'pillar',
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
        ]);

        Livewire::actingAs($user)
            ->test(EditArticlePool::class, ['record' => $pool->getKey()])
            ->fillForm(['name' => 'Nama Diubah'])
            ->call('save')
            ->assertHasNoFormErrors();

        $pool->refresh();
        $this->assertSame('slug-tetap', $pool->slug);
        $this->assertSame('Nama Diubah', $pool->name);
    }

    public function test_edit_page_shows_manual_generate_header_action(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('superadmin');
        $user->givePermissionTo('manage-content');

        $pool = ArticlePool::create([
            'name' => 'Pool Generate',
            'slug' => 'pool-generate-aksi',
            'description' => null,
            'schedule_frequency' => 'daily',
            'schedule_day' => null,
            'schedule_time' => '09:00',
            'schedule_times' => [],
            'content_profile' => 'pillar',
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
        ]);

        Livewire::actingAs($user)
            ->test(EditArticlePool::class, ['record' => $pool->getKey()])
            ->assertSuccessful()
            ->assertSee('Generate artikel sekarang');
    }
}
