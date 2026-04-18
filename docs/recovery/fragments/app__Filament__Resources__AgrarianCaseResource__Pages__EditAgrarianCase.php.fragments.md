# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/Pages/EditAgrarianCase.php`

Total edits captured in transcript: **1**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
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
```

### new_string

```
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
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

