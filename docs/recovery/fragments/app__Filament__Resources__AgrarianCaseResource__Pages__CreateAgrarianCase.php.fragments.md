# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/Pages/CreateAgrarianCase.php`

Total edits captured in transcript: **1**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['case_code'] = 'KSS-'.str_pad(AgrarianCase::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
```

### new_string

```
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
```

---

