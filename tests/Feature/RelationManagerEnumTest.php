<?php

namespace Tests\Feature;

use App\Filament\Resources\AdvocacyProgramResource\RelationManagers\ActionsRelationManager;
use App\Filament\Resources\AgrarianCaseResource\RelationManagers\PartiesRelationManager;
use App\Models\AdvocacyAction;
use App\Models\AdvocacyProgram;
use App\Models\AgrarianCase;
use App\Models\AgrarianCaseParty;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
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
        $case = AgrarianCase::create([
            'title'    => 'Enum Test Case',
            'status'   => 'reported',
            'priority' => 'medium',
        ]);

        foreach ($this->optionsFor(PartiesRelationManager::class, 'party_type') as $value) {
            $party = AgrarianCaseParty::create([
                'agrarian_case_id' => $case->id,
                'party_type'       => $value,
                'name'             => "Party {$value}",
            ]);

            $this->assertNotNull($party->id, "party_type={$value} gagal disimpan");
        }

        $this->assertSame(
            count($this->optionsFor(PartiesRelationManager::class, 'party_type')),
            $case->parties()->count(),
        );
    }

    public function test_all_action_type_options_are_accepted_by_database(): void
    {
        $program = AdvocacyProgram::create([
            'title'  => 'Enum Test Program',
            'status' => 'planned',
        ]);

        foreach ($this->optionsFor(ActionsRelationManager::class, 'action_type') as $value) {
            $action = AdvocacyAction::create([
                'advocacy_program_id' => $program->id,
                'action_date'         => now()->toDateString(),
                'action_type'         => $value,
            ]);

            $this->assertNotNull($action->id, "action_type={$value} gagal disimpan");
        }

        $this->assertSame(
            count($this->optionsFor(ActionsRelationManager::class, 'action_type')),
            $program->actions()->count(),
        );
    }

    /**
     * Ekstrak value dari Select::options() di method `form()` RelationManager.
     *
     * @return array<int,string>
     */
    protected function optionsFor(string $relationManagerClass, string $fieldName): array
    {
        $rm = new ReflectionClass($relationManagerClass);
        $stub = $rm->newInstanceWithoutConstructor();

        $form = $stub->form(Form::make($stub));

        foreach ($form->getComponents() as $component) {
            if ($component instanceof Select && $component->getName() === $fieldName) {
                return array_keys($component->getOptions());
            }
        }

        $this->fail("Field {$fieldName} tidak ditemukan di {$relationManagerClass}::form()");
    }
}
