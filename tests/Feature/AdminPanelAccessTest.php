<?php

namespace Tests\Feature;

use App\Models\AgrarianCase;
use App\Models\Member;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $all = [
            'manage-members', 'manage-cases', 'manage-advocacy',
            'manage-events', 'manage-content', 'manage-settings', 'manage-users',
        ];
        foreach ($all as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());
        Role::findByName('admin', 'web')->syncPermissions(Permission::all());
        Role::findByName('operator', 'web')->syncPermissions([
            'manage-members', 'manage-cases', 'manage-advocacy', 'manage-events', 'manage-content',
        ]);
        Role::findByName('viewer', 'web')->syncPermissions([]);
    }

    public function test_admin_login_page_renders(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_guest_redirected_away_from_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $this->assertContains($response->status(), [302, 403]);
    }

    public function test_inactive_user_cannot_access_panel(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $user->assignRole('superadmin');

        $panel = Filament::getPanel('admin');
        $this->assertFalse($user->canAccessPanel($panel));
    }

    public function test_user_without_role_cannot_access_panel(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $panel = Filament::getPanel('admin');
        $this->assertFalse($user->canAccessPanel($panel));
    }

    public function test_active_user_with_role_can_access_panel(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('viewer');

        $panel = Filament::getPanel('admin');
        $this->assertTrue($user->canAccessPanel($panel));
    }

    public function test_viewer_can_only_view_not_write_or_delete(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('viewer');

        $member = Member::create(['full_name' => 'X', 'gender' => 'male', 'status' => 'pending']);

        $this->assertTrue($user->can('viewAny', Member::class));
        $this->assertTrue($user->can('view', $member));
        $this->assertFalse($user->can('create', Member::class));
        $this->assertFalse($user->can('update', $member));
        $this->assertFalse($user->can('delete', $member));
    }

    public function test_operator_can_create_update_but_not_delete(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('operator');

        $case = AgrarianCase::create([
            'case_code' => 'CASE-POLICY-1',
            'title'    => 'Case',
            'summary' => 'Ringkasan',
            'description' => 'Deskripsi',
            'start_date' => now()->toDateString(),
            'status'   => 'reported',
            'priority' => 'medium',
        ]);

        $this->assertTrue($user->can('create', AgrarianCase::class));
        $this->assertTrue($user->can('update', $case));
        $this->assertFalse($user->can('delete', $case));
    }

    public function test_superadmin_bypasses_all_gates(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('superadmin');

        $member = Member::create(['full_name' => 'Y', 'gender' => 'female', 'status' => 'pending']);

        $this->assertTrue($user->can('create', Member::class));
        $this->assertTrue($user->can('update', $member));
        $this->assertTrue($user->can('delete', $member));
        $this->assertTrue($user->can('manage-settings'));
    }
}
