<?php

namespace App\Filament\Resources\AdvocacyProgramResource\Pages;

use App\Filament\Resources\AdvocacyProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvocacyProgram extends EditRecord
{
    protected static string $resource = AdvocacyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
