    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['case_code'] = 'KSS-'.str_pad(AgrarianCase::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach (($this->data['attachments_upload'] ?? []) as $file) {
            $this->record->addMediaFromDisk($file, 'public')
                ->toMediaCollection('attachments');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }