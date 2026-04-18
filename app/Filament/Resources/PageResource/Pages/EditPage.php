<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

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

    protected function afterSave(): void
    {
        $cover = $this->data['cover_upload'] ?? null;
        if (! is_string($cover) || $cover === '') {
            return;
        }
        if (! Storage::disk('public')->exists($cover)) {
            return;
        }

        $this->record->addMediaFromDisk($cover, 'public')
            ->toMediaCollection('cover');
    }
}