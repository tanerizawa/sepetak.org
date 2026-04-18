<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\WhatsAppOperationsPage;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WhatsAppOperationsPageTest extends TestCase
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

    public function test_whatsapp_operations_page_mounts(): void
    {
        Livewire::test(WhatsAppOperationsPage::class)->assertSuccessful();
    }
}
