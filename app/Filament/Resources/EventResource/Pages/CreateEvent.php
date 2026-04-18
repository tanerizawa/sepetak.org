<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    /**
     * Pindahkan file upload Filament (tersimpan di disk public) ke media
     * collection `photos` agar kolom `photos_count` dan galeri infolist
     * menampilkan jumlah yang akurat. Tanpa ini, upload akan hilang dari
     * model walau file fisiknya ada di storage.
     */
    protected function afterCreate(): void
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
