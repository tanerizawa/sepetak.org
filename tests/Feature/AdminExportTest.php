<?php

namespace Tests\Feature;

use App\Models\AgrarianCase;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        foreach (['manage-members', 'manage-cases', 'manage-advocacy', 'manage-events', 'manage-content', 'manage-settings', 'manage-users'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        Role::findByName('admin', 'web')->syncPermissions(Permission::all());
        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());
    }

    public function test_members_pdf_requires_auth(): void
    {
        $this->get(route('admin.exports.members.pdf'))->assertRedirect();
    }

    public function test_viewer_cannot_access_members_pdf(): void
    {
        $viewer = User::factory()->create(['is_active' => true]);
        assert($viewer instanceof User);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)
            ->get(route('admin.exports.members.pdf'))
            ->assertForbidden();
    }

    public function test_admin_can_download_members_pdf(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        assert($admin instanceof User);
        $admin->syncRoles(['admin']);

        Member::create([
            'full_name' => 'Petani A',
            'gender'    => 'male',
            'status'    => 'active',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.exports.members.pdf'));

        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rekap-anggota-sepetak-', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_download_agrarian_cases_pdf(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        assert($admin instanceof User);
        $admin->syncRoles(['admin']);

        AgrarianCase::create([
            'case_code' => 'CASE-PDF-1',
            'title'  => 'Kasus Uji',
            'summary' => 'Ringkasan',
            'description' => 'Deskripsi',
            'start_date' => now()->toDateString(),
            'status' => 'reported',
            'priority' => 'medium',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.exports.agrarian-cases.pdf'));

        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rekap-kasus-agraria-sepetak-', (string) $response->headers->get('Content-Disposition'));
    }
}
