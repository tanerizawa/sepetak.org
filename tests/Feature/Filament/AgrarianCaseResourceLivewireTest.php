<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\AgrarianCaseResource\Pages\CreateAgrarianCase;
use App\Filament\Resources\AgrarianCaseResource\Pages\EditAgrarianCase;
use App\Filament\Resources\AgrarianCaseResource\Pages\ListAgrarianCases;
use App\Models\AgrarianCase;
use App\Models\User;
use App\Notifications\AgrarianCaseStatusChanged;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AgrarianCaseResourceLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->syncRoles(['admin']);

        $this->actingAs($this->admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_list_agrarian_cases_renders(): void
    {
        AgrarianCase::create([
            'title' => 'Kasus A',
            'status' => 'reported',
            'priority' => 'medium',
        ]);

        Livewire::test(ListAgrarianCases::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(AgrarianCase::all());
    }

    public function test_edit_agrarian_case_status_fires_notification_via_observer(): void
    {
        Notification::fake();

        $case = AgrarianCase::create([
            'title' => 'Sengketa Uji Status',
            'status' => 'reported',
            'priority' => 'medium',
        ]);

        $case->update(['status' => 'legal_process']);

        $this->assertSame('legal_process', $case->fresh()->status);
        Notification::assertSentTo($this->admin, AgrarianCaseStatusChanged::class);
    }

    public function test_view_action_opens_infolist_without_error(): void
    {
        $case = AgrarianCase::create([
            'title' => 'Kasus Dilihat',
            'summary' => 'Ringkasan uji view',
            'status' => 'mediation',
            'priority' => 'high',
            'start_date' => now()->toDateString(),
            'location_text' => 'Desa Telukjaya',
        ]);

        Livewire::test(ListAgrarianCases::class)
            ->mountTableAction('view', $case)
            ->assertSuccessful()
            ->assertSee($case->title);
    }
}
