<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ArticlePoolResource\Pages\ListArticlePools;
use App\Models\ArticlePool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ArticlePoolListPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('superadmin', 'web');
        Permission::findOrCreate('manage-content', 'web');
    }

    public function test_list_page_shows_generate_table_action(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('superadmin');
        $user->givePermissionTo('manage-content');

        ArticlePool::create([
            'name' => 'Pool List',
            'slug' => 'pool-list-aksi',
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
            ->test(ListArticlePools::class)
            ->assertSuccessful()
            ->assertSee('Generate sekarang');
    }
}
