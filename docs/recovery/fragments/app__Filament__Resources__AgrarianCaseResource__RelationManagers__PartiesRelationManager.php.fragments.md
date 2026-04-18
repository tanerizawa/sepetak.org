# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/RelationManagers/PartiesRelationManager.php`

Total edits captured in transcript: **2**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
            Forms\Components\Select::make('party_type')
                ->label('Tipe Pihak')
                ->options([
                    'plaintiff'  => 'Penggugat',
                    'defendant'  => 'Tergugat',
                    'witness'    => 'Saksi',
                    'mediator'   => 'Mediator',
                    'other'      => 'Lainnya',
                ])
                ->required(),
```

### new_string

```
            Forms\Components\Select::make('party_type')
                ->label('Tipe Pihak')
                ->options([
                    'member'      => 'Anggota',
                    'community'   => 'Komunitas / Warga',
                    'institution' => 'Lembaga / LSM',
                    'company'     => 'Perusahaan',
                    'government'  => 'Pemerintah',
                    'other'       => 'Lainnya',
                ])
                ->required(),
```

---

## Edit #2

### old_string

```
                Tables\Columns\TextColumn::make('party_type')
                    ->label('Tipe'),
```

### new_string

```
                Tables\Columns\TextColumn::make('party_type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'member'      => 'Anggota',
                        'community'   => 'Komunitas / Warga',
                        'institution' => 'Lembaga / LSM',
                        'company'     => 'Perusahaan',
                        'government'  => 'Pemerintah',
                        'other'       => 'Lainnya',
                        default       => $state,
                    }),
```

---

