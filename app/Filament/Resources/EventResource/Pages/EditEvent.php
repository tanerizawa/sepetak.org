<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Lihat CreateEvent::afterCreate — sama, pastikan foto lapangan yang
     * diunggah kembali diindeks ke media collection `photos`.
     */
    protected function afterSave(): void
    {
        foreach (($this->data['photos_upload'] ?? []) as $file) {
            $this->record->addMediaFromDisk($file, 'public')
                ->toMediaCollection('photos');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
