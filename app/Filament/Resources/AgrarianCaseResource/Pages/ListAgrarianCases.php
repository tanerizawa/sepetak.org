<?php

namespace App\Filament\Resources\AgrarianCaseResource\Pages;

use App\Filament\Resources\AgrarianCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgrarianCases extends ListRecords
{
    protected static string $resource = AgrarianCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
