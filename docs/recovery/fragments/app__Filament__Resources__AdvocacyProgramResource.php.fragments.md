# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/AdvocacyProgramResource.php`

Total edits captured in transcript: **9**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
                    Forms\Components\TextInput::make('location_text')
                        ->label('Lokasi')
                        ->maxLength(255),
                ])
                ->columns(2),
```

### new_string

```
                    Forms\Components\TextInput::make('location_text')
                        ->label('Lokasi')
                        ->maxLength(255),

                    Forms\Components\Select::make('lead_user_id')
                        ->label('Penanggung Jawab')
                        ->relationship('leadUser', 'name')
                        ->searchable()
                        ->preload(),
                ])
                ->columns(2),
```

---

## Edit #2

### old_string

```
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
            ])
```

### new_string

```
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('leadUser.name')
                    ->label('Penanggung Jawab')
                    ->sortable(),
            ])
```

---

## Edit #3

### old_string

```
use App\Filament\Resources\AdvocacyProgramResource\Pages;
use App\Filament\Resources\AdvocacyProgramResource\RelationManagers;
use App\Models\AdvocacyProgram;
```

### new_string

```
use App\Exports\AdvocacyProgramsExport;
use App\Filament\Resources\AdvocacyProgramResource\Pages;
use App\Filament\Resources\AdvocacyProgramResource\RelationManagers;
use App\Models\AdvocacyProgram;
```

---

## Edit #4

### old_string

```
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
```

### new_string

```
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn () => (new AdvocacyProgramsExport())->download('program-advokasi-sepetak-' . now()->format('Ymd-His') . '.xlsx')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
```

---

## Edit #5

### old_string

```
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => 'planned',
                        'success' => 'active',
                        'warning' => 'paused',
                        'gray' => 'completed',
                    ]),
```

### new_string

```
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'planned' => 'info',
                        'active' => 'success',
                        'paused' => 'warning',
                        'completed' => 'gray',
                        default => 'gray',
                    }),
```

---

## Edit #6

### old_string

```
                Tables\Columns\TextColumn::make('leadUser.name')
                    ->label('Penanggung Jawab')
                    ->sortable(),
            ])
            ->filters([
```

### new_string

```
                Tables\Columns\TextColumn::make('leadUser.name')
                    ->label('Penanggung Jawab')
                    ->sortable(),

                Tables\Columns\TextColumn::make('photos_count')
                    ->label('Foto')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn (AdvocacyProgram $record) => $record->getMedia('photos')->count()),
            ])
            ->filters([
```

---

## Edit #7

### old_string

```
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn () => (new AdvocacyProgramsExport)->download('program-advokasi-sepetak-'.now()->format('Ymd-His').'.xlsx')),
            ])
```

### new_string

```
            ->actions([
                Tables\Actions\Action::make('exportActions')
                    ->label('Aksi (xlsx)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn (AdvocacyProgram $record) => (new AdvocacyActionsExport($record))
                        ->download('aksi-advokasi-'.$record->id.'-'.now()->format('Ymd-His').'.xlsx')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn () => (new AdvocacyProgramsExport)->download('program-advokasi-sepetak-'.now()->format('Ymd-His').'.xlsx')),

                Tables\Actions\Action::make('exportAllActions')
                    ->label('Export Semua Aksi')
                    ->icon('heroicon-o-megaphone')
                    ->color('info')
                    ->action(fn () => (new AdvocacyActionsExport)
                        ->download('aksi-advokasi-semua-'.now()->format('Ymd-His').'.xlsx')),
            ])
```

---

## Edit #8

### old_string

```
use App\Exports\AdvocacyProgramsExport;
```

### new_string

```
use App\Exports\AdvocacyActionsExport;
use App\Exports\AdvocacyProgramsExport;
```

---

## Edit #9

### old_string

```
            ->actions([
                Tables\Actions\Action::make('exportActions')
                    ->label('Aksi (xlsx)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn (AdvocacyProgram $record) => (new AdvocacyActionsExport($record))
                        ->download('aksi-advokasi-'.$record->id.'-'.now()->format('Ymd-His').'.xlsx')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
```

### new_string

```
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('exportActions')
                    ->label('Export Aksi')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn (AdvocacyProgram $record) => (new AdvocacyActionsExport($record))
                        ->download('aksi-advokasi-'.$record->id.'-'.now()->format('Ymd-His').'.xlsx')),
                Tables\Actions\DeleteAction::make(),
            ])
```

---

