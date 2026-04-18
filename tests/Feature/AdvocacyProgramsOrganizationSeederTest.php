<?php

namespace Tests\Feature;

use App\Models\AdvocacyAction;
use App\Models\AdvocacyProgram;
use Database\Seeders\AdvocacyProgramsOrganizationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvocacyProgramsOrganizationSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_twelve_programs_and_is_idempotent(): void
    {
        $this->seed(AdvocacyProgramsOrganizationSeeder::class);

        $this->assertSame(12, AdvocacyProgram::query()->where('program_code', 'like', 'ORG-PRG-%')->count());
        $this->assertGreaterThanOrEqual(5, AdvocacyAction::query()->count());

        $this->seed(AdvocacyProgramsOrganizationSeeder::class);

        $this->assertSame(12, AdvocacyProgram::query()->where('program_code', 'like', 'ORG-PRG-%')->count());
    }
}
