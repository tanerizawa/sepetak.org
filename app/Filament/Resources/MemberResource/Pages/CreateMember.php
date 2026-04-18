<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Address;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['member_code']);

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        return $data;
    }

    /**
     * @return list<string>
     */
    protected function uploadStatePaths(string $key): array
    {
        $raw = $this->form->getRawState();
        $state = $raw[$key] ?? ($this->data[$key] ?? null);

        return $this->normalizeUploadState($state);
    }

    /**
     * @return list<string>
     */
    protected function normalizeUploadState(mixed $state): array
    {
        if ($state === null || $state === '' || $state === []) {
            return [];
        }

        if (is_string($state)) {
            return [$state];
        }

        if (is_array($state)) {
            return array_values(array_filter($state, static fn ($v): bool => is_string($v) && $v !== ''));
        }

        return [];
    }

    protected function afterCreate(): void
    {
        foreach ($this->uploadStatePaths('photo_upload') as $path) {
            if (Storage::disk('public')->exists($path)) {
                $this->record->addMediaFromDisk($path, 'public')
                    ->toMediaCollection('photo');

                break;
            }
        }

        foreach ($this->uploadStatePaths('documents_upload') as $path) {
            if (Storage::disk('public')->exists($path)) {
                $this->record->addMediaFromDisk($path, 'public')
                    ->toMediaCollection('documents');
            }
        }

        $addressFields = [
            'line_1' => $this->data['address_line_1'] ?? null,
            'village' => $this->data['address_village'] ?? null,
            'district' => $this->data['address_district'] ?? null,
            'regency' => $this->data['address_regency'] ?? null,
            'province' => $this->data['address_province'] ?? null,
            'postal_code' => $this->data['address_postal_code'] ?? null,
        ];

        if (array_filter($addressFields)) {
            $address = Address::create($addressFields);
            $this->record->update(['address_id' => $address->id]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
