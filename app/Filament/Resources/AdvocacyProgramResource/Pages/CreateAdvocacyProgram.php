<?php

namespace App\Filament\Resources\AdvocacyProgramResource\Pages;

use App\Filament\Resources\AdvocacyProgramResource;
use App\Models\AdvocacyProgram;
use Filament\Resources\Pages\CreateRecord;

class CreateAdvocacyProgram extends CreateRecord
{
    protected static string $resource = AdvocacyProgramResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['program_code'] = 'PRG-'.str_pad(AdvocacyProgram::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
