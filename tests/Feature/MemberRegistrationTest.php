<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_form_page_renders(): void
    {
        $response = $this->get(route('member-registration.create'));

        $response->assertOk();
        // Setelah redesign "Tani Merah" (Apr 2026) judul diganti dengan slogan.
        // Tes cek kehadiran indikator form pendaftaran alih-alih copy spesifik
        // supaya heading dapat di-tweak tim redaksi tanpa memecah test.
        $response->assertSee('Permohonan Keanggotaan', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('name="full_name"', false);
    }

    public function test_valid_submission_creates_member_and_address(): void
    {
        $payload = [
            'full_name'        => 'Petani Uji',
            'gender'           => 'male',
            'phone'            => '081234567890',
            'email'            => 'petani@uji.test',
            'address_village'  => 'Desa Uji',
            'address_district' => 'Kec Uji',
            'address_regency'  => 'Karawang',
            'notes'            => 'Saya tertarik bergabung.',
        ];

        $response = $this->post(route('member-registration.store'), $payload);

        $response->assertRedirect(route('member-registration.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('members', 1);
        $member = Member::first();
        $this->assertStringStartsWith('ANG-', $member->member_code);
        $this->assertSame('pending', $member->status);
        $this->assertSame('Petani Uji', $member->full_name);

        $this->assertDatabaseHas('addresses', [
            'village'  => 'Desa Uji',
            'district' => 'Kec Uji',
            'regency'  => 'Karawang',
        ]);

        $this->assertNotNull($member->address_id);
        $this->assertSame(Address::first()->id, $member->address_id);
    }

    public function test_submission_without_address_fields_does_not_create_address(): void
    {
        $payload = [
            'full_name' => 'Tanpa Alamat',
            'gender'    => 'female',
        ];

        $response = $this->post(route('member-registration.store'), $payload);

        $response->assertRedirect(route('member-registration.create'));

        $this->assertDatabaseCount('members', 1);
        $this->assertDatabaseCount('addresses', 0);
        $this->assertNull(Member::first()->address_id);
    }

    public function test_validation_errors_for_missing_required_fields(): void
    {
        $response = $this->from(route('member-registration.create'))
            ->post(route('member-registration.store'), []);

        $response->assertRedirect(route('member-registration.create'));
        $response->assertSessionHasErrors(['full_name', 'gender']);
        $this->assertDatabaseCount('members', 0);
    }
}
