<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\AdminNewMemberRegistered;
use App\Notifications\MemberRegistrationReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MemberRegistrationNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'operator', 'viewer'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
    }

    public function test_member_and_admins_receive_email_notification_on_submit(): void
    {
        Notification::fake();

        SiteSetting::create(['setting_key' => 'contact_email', 'setting_value' => 'contact@example.org', 'group_name' => 'contact']);

        $admin = User::factory()->create(['email' => 'admin@example.org', 'is_active' => true]);
        $admin->syncRoles(['admin']);

        $viewer = User::factory()->create(['email' => 'viewer@example.org', 'is_active' => true]);
        $viewer->syncRoles(['viewer']);

        $response = $this->post(route('member-registration.store'), [
            'full_name' => 'Calon Anggota',
            'gender'    => 'male',
            'email'     => 'calon@example.test',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('members', 1);
        $member = Member::first();

        Notification::assertSentOnDemand(
            MemberRegistrationReceived::class,
            fn ($notification, array $channels, $notifiable) => in_array('calon@example.test', (array) $notifiable->routes['mail'], true)
                && $notification->member->is($member),
        );

        Notification::assertSentOnDemand(
            AdminNewMemberRegistered::class,
            fn ($notification, array $channels, $notifiable) => in_array('contact@example.org', (array) $notifiable->routes['mail'], true),
        );

        Notification::assertSentTo($admin, AdminNewMemberRegistered::class);

        Notification::assertNotSentTo($viewer, AdminNewMemberRegistered::class);
    }

    public function test_member_without_email_still_registers_and_notifies_admins(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['email' => 'admin2@example.org', 'is_active' => true]);
        $admin->syncRoles(['superadmin']);

        $response = $this->post(route('member-registration.store'), [
            'full_name' => 'Tanpa Email',
            'gender'    => 'female',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('members', 1);

        Notification::assertNothingSentTo(User::factory()->make(['email' => 'someone@test.test']));

        Notification::assertSentTo($admin, AdminNewMemberRegistered::class);
    }
}
