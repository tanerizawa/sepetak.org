<?php

namespace App\Filament\Resources\AgrarianCaseResource\Pages;

use App\Filament\Resources\AgrarianCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgrarianCase extends EditRecord
{
    protected static string $resource = AgrarianCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
