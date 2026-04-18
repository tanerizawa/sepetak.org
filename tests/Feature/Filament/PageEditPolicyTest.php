<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Models\Page;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PageEditPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function seedRoles(): void
    {
        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        Permission::findOrCreate('manage-content', 'web');
        Role::findByName('operator', 'web')->givePermissionTo('manage-content');
    }

    public function test_operator_can_save_page_even_when_author_is_another_user(): void
    {
        $this->seedRoles();

        $owner = User::factory()->create(['is_active' => true]);
        $owner->syncRoles(['superadmin']);

        $operator = User::factory()->create(['is_active' => true]);
        $operator->syncRoles(['operator']);

        $page = Page::create([
            'title' => 'Milik Owner',
            'slug' => 'milik-owner',
            'body' => '<p>awal</p>',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => $owner->id,
        ]);

        $this->actingAs($operator);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $this->assertTrue($operator->can('update', $page));

        Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm([
                'title' => 'Milik Owner',
                'slug' => 'milik-owner',
                'body' => '<p>diubah operator</p>',
                'status' => 'published',
                'author_id' => $owner->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertStringContainsString('diubah operator', $page->fresh()->body);
    }
}
