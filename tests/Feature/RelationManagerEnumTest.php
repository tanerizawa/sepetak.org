<?php

namespace Tests\Feature;

use App\Models\AdvocacyAction;
use App\Models\AdvocacyProgram;
use App\Models\AgrarianCase;
use App\Models\AgrarianCaseParty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test: memastikan daftar `options()` pada Select di RelationManager
 * selalu konsisten dengan enum / CHECK constraint di database.
 *
 * Bug historis (diperbaiki): opsi `plaintiff/defendant/...` pada
 * PartiesRelationManager dan `legal_filing/negotiation/...` pada
 * ActionsRelationManager melanggar CHECK constraint migrasi PostgreSQL.
 */
class RelationManagerEnumTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_party_type_options_are_accepted_by_database(): void
    {
        $partyTypes = ['member', 'community', 'institution', 'company', 'government', 'other'];

        $case = AgrarianCase::create([
            'case_code' => 'CASE-ENUM-1',
            'title'    => 'Enum Test Case',
            'summary' => 'Ringkasan',
            'description' => 'Deskripsi',
            'start_date' => now()->toDateString(),
            'status'   => 'reported',
            'priority' => 'medium',
        ]);

        foreach ($partyTypes as $value) {
            $party = AgrarianCaseParty::create([
                'agrarian_case_id' => $case->id,
                'party_type'       => $value,
                'name'             => "Party {$value}",
            ]);

            $this->assertNotNull($party->id, "party_type={$value} gagal disimpan");
        }

        $this->assertSame(
            count($partyTypes),
            $case->parties()->count(),
        );
    }

    public function test_all_action_type_options_are_accepted_by_database(): void
    {
        $actionTypes = ['meeting', 'training', 'campaign', 'field_visit', 'legal', 'other'];

        $program = AdvocacyProgram::create([
            'program_code' => 'PRG-ENUM-1',
            'title'  => 'Enum Test Program',
            'description' => 'Deskripsi',
            'status' => 'planned',
            'start_date' => now()->toDateString(),
        ]);

        foreach ($actionTypes as $value) {
            $action = AdvocacyAction::create([
                'advocacy_program_id' => $program->id,
                'action_date'         => now()->toDateString(),
                'action_type'         => $value,
            ]);

            $this->assertNotNull($action->id, "action_type={$value} gagal disimpan");
        }

        $this->assertSame(
            count($actionTypes),
            $program->actions()->count(),
        );
    }
}
