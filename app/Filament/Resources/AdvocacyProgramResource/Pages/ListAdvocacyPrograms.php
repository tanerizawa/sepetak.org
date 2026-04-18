<?php

namespace App\Filament\Resources\AdvocacyProgramResource\Pages;

use App\Filament\Resources\AdvocacyProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvocacyPrograms extends ListRecords
{
    protected static string $resource = AdvocacyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
