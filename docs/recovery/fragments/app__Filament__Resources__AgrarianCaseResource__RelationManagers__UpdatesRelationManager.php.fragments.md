# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/RelationManagers/UpdatesRelationManager.php`

Total edits captured in transcript: **1**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => 'reported',
                        'warning' => 'under_review',
                        'primary' => 'mediation',
                        'danger' => 'legal_process',
                        'success' => 'resolved',
                        'gray' => 'closed',
                    ]),
```

### new_string

```
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'reported' => 'info',
                        'under_review' => 'warning',
                        'mediation' => 'primary',
                        'legal_process' => 'danger',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),
```

---

