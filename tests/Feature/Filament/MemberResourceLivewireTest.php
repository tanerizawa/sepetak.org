<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\MemberResource\Pages\CreateMember;
use App\Filament\Resources\MemberResource\Pages\EditMember;
use App\Filament\Resources\MemberResource\Pages\ListMembers;
use App\Models\Member;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MemberResourceLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        foreach (['manage-members', 'manage-cases', 'manage-content'] as $p) {
            Permission::findOrCreate($p, 'web');
        }
        Role::findByName('admin', 'web')->syncPermissions(Permission::all());

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->syncRoles(['admin']);

        $this->actingAs($this->admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_list_members_renders_for_admin(): void
    {
        Member::create([
            'full_name' => 'Anggota Dari List',
            'gender' => 'male',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        Livewire::test(ListMembers::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(Member::all());
    }

    public function test_create_member_via_form(): void
    {
        Livewire::test(CreateMember::class)
            ->fillForm([
                'full_name' => 'Anggota Baru Via Livewire',
                'gender' => 'female',
                'status' => 'pending',
                'joined_at' => now()->toDateString(),
                'address_village' => 'Desa Contoh',
                'address_district' => 'Kec Contoh',
                'address_regency' => 'Karawang',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('members', [
            'full_name' => 'Anggota Baru Via Livewire',
            'gender' => 'female',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('addresses', [
            'village' => 'Desa Contoh',
            'regency' => 'Karawang',
        ]);
    }

    public function test_edit_member_updates_status(): void
    {
        $member = Member::create([
            'full_name' => 'Pending User',
            'gender' => 'male',
            'status' => 'pending',
            'joined_at' => now(),
        ]);

        Livewire::test(EditMember::class, ['record' => $member->getRouteKey()])
            ->fillForm(['status' => 'active'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('active', $member->fresh()->status);
    }

    public function test_view_action_opens_infolist_without_error(): void
    {
        $member = Member::create([
            'full_name' => 'Anggota Dilihat',
            'gender' => 'female',
            'status' => 'active',
            'joined_at' => now(),
            'phone' => '081200000000',
            'email' => 'dilihat@example.org',
        ]);

        Livewire::test(ListMembers::class)
            ->mountTableAction('view', $member)
            ->assertSuccessful()
            ->assertSee($member->full_name);
    }
}
