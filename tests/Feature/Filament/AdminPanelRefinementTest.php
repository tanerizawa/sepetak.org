<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\EventResource\Pages\CreateEvent;
use App\Filament\Resources\MemberResource\Pages\ListMembers;
use App\Filament\Resources\PageResource\Pages\ListPages;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\Event;
use App\Models\Member;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Regresi panel admin — audit 16 April 2026:
 *   - Badge 'secondary' tidak valid di Filament 3 (Member/Post/Page)
 *   - EventResource photos_upload orphan (tidak masuk media library)
 *   - UserResource tanpa role selector → user tidak bisa akses panel
 *   - PageResource tidak punya bulk publish/archive
 *   - PostResource tidak punya bulk publish/archive
 */
class AdminPanelRefinementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        foreach (['manage-members', 'manage-cases', 'manage-advocacy', 'manage-events', 'manage-content', 'manage-settings', 'manage-users'] as $p) {
            Permission::findOrCreate($p, 'web');
        }
        Role::findByName('superadmin', 'web')->syncPermissions(Permission::all());

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->syncRoles(['superadmin']);

        $this->actingAs($this->admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    /**
     * Regresi #1: badge Member dengan status 'deceased' tidak boleh throw
     * error karena color('secondary') dulu dipakai — sekarang harus 'gray'.
     */
    public function test_member_list_renders_with_deceased_status(): void
    {
        Member::create([
            'full_name' => 'Alm. Anggota Uji',
            'gender' => 'male',
            'status' => 'deceased',
            'joined_at' => now()->subYears(3),
        ]);

        Livewire::test(ListMembers::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(Member::all());
    }

    /**
     * Regresi #1b: badge Post/Page dengan status 'archived' tidak boleh throw.
     */
    public function test_post_and_page_list_render_with_archived_status(): void
    {
        Post::create([
            'title' => 'Artikel Diarsipkan',
            'slug' => 'artikel-diarsipkan',
            'body' => 'body',
            'status' => 'archived',
            'author_id' => $this->admin->id,
        ]);

        Page::create([
            'title' => 'Halaman Diarsipkan',
            'slug' => 'halaman-diarsipkan',
            'body' => 'body',
            'status' => 'archived',
        ]);

        Livewire::test(ListPosts::class)->assertSuccessful();
        Livewire::test(ListPages::class)->assertSuccessful();
    }

    /**
     * Regresi #3: membuat user via admin harus menyimpan role.
     */
    public function test_create_user_assigns_role_via_afterCreate(): void
    {
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Pengguna Baru',
                'email' => 'baru@sepetak.org',
                'password' => 'rahasia-kuat-123',
                'is_active' => true,
                'roles' => ['operator'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::where('email', 'baru@sepetak.org')->firstOrFail();

        $this->assertTrue($user->hasRole('operator'));
        $this->assertTrue((bool) $user->is_active);
    }

    /**
     * Regresi #3b: mengubah role user via Edit harus sync, bukan append.
     */
    public function test_edit_user_syncs_roles(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->syncRoles(['viewer']);

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'roles' => ['operator'],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();
        $this->assertTrue($user->hasRole('operator'));
        $this->assertFalse($user->hasRole('viewer'));
    }

    /**
     * Kolom role di ListUsers harus render tanpa error (regresi ketika badge
     * default color bukan mapping yang ada di daftar role).
     */
    public function test_list_users_renders_role_column(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->syncRoles(['admin']);

        Livewire::test(ListUsers::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$user]);
    }

    /**
     * Regresi #2: membuat Event tanpa upload foto tidak boleh error
     * (afterCreate harus defensif terhadap key photos_upload yang tidak ada).
     */
    public function test_create_event_without_photos_does_not_error(): void
    {
        Livewire::test(CreateEvent::class)
            ->fillForm([
                'title' => 'Rapat Koordinasi',
                'description' => '<p>Agenda rapat bulanan pengurus.</p>',
                'event_date' => now()->addDays(3)->toDateTimeString(),
                'location_text' => 'Sekretariat Karawang',
                'status' => 'planned',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('events', [
            'title' => 'Rapat Koordinasi',
            'status' => 'planned',
        ]);

        $event = Event::where('title', 'Rapat Koordinasi')->first();
        $this->assertNotNull($event);
        $this->assertSame(0, $event->getMedia('photos')->count());
    }
}
