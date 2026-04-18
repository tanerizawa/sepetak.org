# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/AdvocacyProgramResource/RelationManagers/ActionsRelationManager.php`

Total edits captured in transcript: **3**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
            Forms\Components\Select::make('action_type')
                ->label('Tipe Aksi')
                ->options([
                    'meeting'       => 'Rapat',
                    'field_visit'   => 'Kunjungan Lapangan',
                    'legal_filing'  => 'Pengajuan Hukum',
                    'negotiation'   => 'Negosiasi',
                    'workshop'      => 'Workshop',
                    'other'         => 'Lainnya',
                ])
                ->required(),
```

### new_string

```
            Forms\Components\Select::make('action_type')
                ->label('Tipe Aksi')
                ->options([
                    'meeting'     => 'Rapat',
                    'training'    => 'Pelatihan',
                    'campaign'    => 'Kampanye',
                    'field_visit' => 'Kunjungan Lapangan',
                    'legal'       => 'Proses Hukum',
                    'other'       => 'Lainnya',
                ])
                ->required(),
```

---

## Edit #2

### old_string

```
                Tables\Columns\BadgeColumn::make('action_type')
                    ->label('Tipe Aksi'),
```

### new_string

```
                Tables\Columns\BadgeColumn::make('action_type')
                    ->label('Tipe Aksi')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'meeting'     => 'Rapat',
                        'training'    => 'Pelatihan',
                        'campaign'    => 'Kampanye',
                        'field_visit' => 'Kunjungan Lapangan',
                        'legal'       => 'Proses Hukum',
                        'other'       => 'Lainnya',
                        default       => $state,
                    }),
```

---

## Edit #3

### old_string

```
                Tables\Columns\BadgeColumn::make('action_type')
                    ->label('Tipe Aksi')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'meeting' => 'Rapat',
                        'training' => 'Pelatihan',
                        'campaign' => 'Kampanye',
                        'field_visit' => 'Kunjungan Lapangan',
                        'legal' => 'Proses Hukum',
                        'other' => 'Lainnya',
                        default => $state,
                    }),
```

### new_string

```
                Tables\Columns\TextColumn::make('action_type')
                    ->label('Tipe Aksi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'meeting' => 'Rapat',
                        'training' => 'Pelatihan',
                        'campaign' => 'Kampanye',
                        'field_visit' => 'Kunjungan Lapangan',
                        'legal' => 'Proses Hukum',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'meeting' => 'info',
                        'training' => 'success',
                        'campaign' => 'primary',
                        'field_visit' => 'warning',
                        'legal' => 'danger',
                        default => 'gray',
                    }),
```

---

