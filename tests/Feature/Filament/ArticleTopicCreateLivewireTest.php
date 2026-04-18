<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ArticleTopicResource\Pages\CreateArticleTopic;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ArticleTopicCreateLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        foreach (['manage-content'] as $p) {
            Permission::findOrCreate($p, 'web');
        }
        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());

        $admin = User::factory()->create(['is_active' => true]);
        $admin->syncRoles(['superadmin']);
        $this->actingAs($admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_create_article_topic_page_mounts_without_error(): void
    {
        Livewire::test(CreateArticleTopic::class)->assertSuccessful();
    }
}
