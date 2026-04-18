<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\MemberResource\Pages\CreateMember;
use App\Models\Member;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MemberCreateLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        foreach (['manage-members'] as $p) {
            Permission::findOrCreate($p, 'web');
        }
        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());

        $admin = User::factory()->create(['is_active' => true]);
        assert($admin instanceof User);
        $admin->syncRoles(['superadmin']);
        $this->actingAs($admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_create_member_succeeds_with_minimal_valid_data(): void
    {
        Livewire::test(CreateMember::class)
            ->fillForm([
                'full_name' => 'Anggota Uji Filament',
                'gender' => 'male',
                'status' => 'pending',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame(1, Member::query()->count());
        $member = Member::query()->first();
        $this->assertStringStartsWith('ANG-', (string) $member->member_code);
        $this->assertTrue((bool) $member->whatsapp_notifications);
    }

    public function test_create_member_with_nik_succeeds(): void
    {
        Livewire::test(CreateMember::class)
            ->fillForm([
                'full_name' => 'Anggota Dengan NIK',
                'gender' => 'female',
                'status' => 'pending',
                'nik' => '3201010101010101',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $member = Member::query()->where('full_name', 'Anggota Dengan NIK')->first();
        $this->assertNotNull($member);
        $this->assertSame('3201010101010101', $member->nik);
    }
}
