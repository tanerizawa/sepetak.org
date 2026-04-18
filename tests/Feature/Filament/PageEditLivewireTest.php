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

class PageEditLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        foreach (['manage-content'] as $p) {
            Permission::findOrCreate($p, 'web');
        }
        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->syncRoles(['superadmin']);

        $this->actingAs($this->admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_edit_page_save_with_long_html_body(): void
    {
        $longBody = '<p>'.str_repeat('x', 8000).'</p>';

        $page = Page::create([
            'title' => 'Halaman Uji',
            'slug' => 'halaman-uji-edit',
            'body' => '<p>Awal</p>',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => $this->admin->id,
        ]);

        Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm([
                'title' => 'Halaman Uji',
                'slug' => 'halaman-uji-edit',
                'body' => $longBody,
                'status' => 'published',
                'author_id' => $this->admin->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertStringContainsString('xxxxxxxx', $page->fresh()->body);
    }
}
