<?php

namespace Tests\Feature;

use App\Models\AgrarianCase;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\AgrarianCaseStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AgrarianCaseStatusNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
    }

    public function test_status_change_notifies_lead_admin_and_contact(): void
    {
        Notification::fake();

        SiteSetting::create([
            'setting_key' => 'contact_email',
            'setting_value' => 'contact@example.org',
            'group_name' => 'contact',
        ]);

        $admin = User::factory()->create(['email' => 'admin@ex.org', 'is_active' => true]);
        $admin->syncRoles(['admin']);

        $lead = User::factory()->create(['email' => 'lead@ex.org', 'is_active' => true]);
        $lead->syncRoles(['operator']);

        $viewer = User::factory()->create(['email' => 'viewer@ex.org', 'is_active' => true]);
        $viewer->syncRoles(['viewer']);

        $case = AgrarianCase::create([
            'case_code' => 'CASE-001',
            'title' => 'Kasus Telukjaya',
            'summary' => 'Ringkasan',
            'description' => 'Deskripsi',
            'start_date' => now()->toDateString(),
            'status' => 'reported',
            'priority' => 'medium',
            'lead_user_id' => $lead->id,
        ]);

        $case->update(['status' => 'mediation']);

        Notification::assertSentTo($admin, AgrarianCaseStatusChanged::class);
        Notification::assertSentTo($lead, AgrarianCaseStatusChanged::class);
        Notification::assertNotSentTo($viewer, AgrarianCaseStatusChanged::class);

        Notification::assertSentOnDemand(
            AgrarianCaseStatusChanged::class,
            fn ($notification, array $channels, $notifiable) => in_array('contact@example.org', (array) $notifiable->routes['mail'], true)
                && $notification->newStatus === 'mediation'
                && $notification->previousStatus === 'reported',
        );
    }

    public function test_no_notification_when_other_field_changes(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['is_active' => true]);
        $admin->syncRoles(['admin']);

        $case = AgrarianCase::create([
            'case_code' => 'CASE-002',
            'title' => 'Kasus Tanpa Perubahan Status',
            'summary' => 'Ringkasan',
            'description' => 'Deskripsi',
            'start_date' => now()->toDateString(),
            'status' => 'reported',
            'priority' => 'medium',
        ]);

        $case->update(['title' => 'Kasus diubah judulnya saja']);

        Notification::assertNothingSentTo($admin);
    }
}
