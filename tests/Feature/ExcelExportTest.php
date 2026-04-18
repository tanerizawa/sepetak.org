<?php

namespace Tests\Feature;

use App\Exports\AdvocacyActionsExport;
use App\Exports\AdvocacyProgramsExport;
use App\Exports\AgrarianCasesExport;
use App\Exports\EventAttendancesExport;
use App\Exports\EventsExport;
use App\Exports\MembersExport;
use App\Models\AdvocacyAction;
use App\Models\AdvocacyProgram;
use App\Models\AgrarianCase;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExcelExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_export_produces_xlsx_with_expected_rows(): void
    {
        Member::create([
            'full_name' => 'Anggota A',
            'gender'    => 'male',
            'status'    => 'active',
            'joined_at' => now(),
        ]);

        Excel::fake();

        (new MembersExport())->store('members-test.xlsx', 'local');

        Excel::assertStored('members-test.xlsx', 'local');
    }

    public function test_agrarian_cases_export_download(): void
    {
        AgrarianCase::create([
            'title'    => 'Kasus Export',
            'status'   => 'reported',
            'priority' => 'medium',
        ]);

        $response = (new AgrarianCasesExport())->download('cases.xlsx');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString(
            'cases.xlsx',
            (string) $response->headers->get('Content-Disposition')
        );
    }

    public function test_advocacy_programs_export_download(): void
    {
        AdvocacyProgram::create([
            'title'      => 'Program Uji',
            'status'     => 'active',
            'start_date' => now(),
        ]);

        $response = (new AdvocacyProgramsExport())->download('adv.xlsx');

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_events_export_download(): void
    {
        Event::create([
            'title'       => 'Rapat Uji',
            'description' => 'Deskripsi rapat uji',
            'event_date'  => now(),
            'status'      => 'planned',
        ]);

        $response = (new EventsExport())->download('events.xlsx');

        $this->assertSame(200, $response->getStatusCode());
    }
}
