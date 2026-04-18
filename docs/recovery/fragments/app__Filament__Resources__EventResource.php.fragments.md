# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/EventResource.php`

Total edits captured in transcript: **7**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
```

### new_string

```
use App\Exports\EventsExport;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
```

---

## Edit #2

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
                    ->action(fn () => (new EventsExport())->download('kegiatan-sepetak-' . now()->format('Ymd-His') . '.xlsx')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
```

---

## Edit #3

### old_string

```
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => 'planned',
                        'success' => 'done',
                        'danger' => 'canceled',
                    ]),
```

### new_string

```
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'planned' => 'info',
                        'done' => 'success',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),
```

---

## Edit #4

### old_string

```
                Tables\Columns\TextColumn::make('organizer.name')
                    ->label('Penyelenggara')
                    ->sortable(),
            ])
```

### new_string

```
                Tables\Columns\TextColumn::make('organizer.name')
                    ->label('Penyelenggara')
                    ->sortable(),

                Tables\Columns\TextColumn::make('attendances_count')
                    ->label('Kehadiran')
                    ->badge()
                    ->color('primary')
                    ->counts('attendances'),

                Tables\Columns\TextColumn::make('photos_count')
                    ->label('Foto')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn (Event $record) => $record->getMedia('photos')->count()),
            ])
```

---

## Edit #5

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
                    ->action(fn () => (new EventsExport)->download('kegiatan-sepetak-'.now()->format('Ymd-His').'.xlsx')),
            ])
```

### new_string

```
            ->actions([
                Tables\Actions\Action::make('exportAttendance')
                    ->label('Kehadiran (xlsx)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn (Event $record) => (new EventAttendancesExport($record))
                        ->download('kehadiran-'.$record->id.'-'.now()->format('Ymd-His').'.xlsx')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn () => (new EventsExport)->download('kegiatan-sepetak-'.now()->format('Ymd-His').'.xlsx')),

                Tables\Actions\Action::make('exportAllAttendance')
                    ->label('Export Semua Kehadiran')
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->action(fn () => (new EventAttendancesExport)
                        ->download('kehadiran-semua-kegiatan-'.now()->format('Ymd-His').'.xlsx')),
            ])
```

---

## Edit #6

### old_string

```
use App\Exports\EventsExport;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
```

### new_string

```
use App\Exports\EventAttendancesExport;
use App\Exports\EventsExport;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
```

---

## Edit #7

### old_string

```
            ->actions([
                Tables\Actions\Action::make('exportAttendance')
                    ->label('Kehadiran (xlsx)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn (Event $record) => (new EventAttendancesExport($record))
                        ->download('kehadiran-'.$record->id.'-'.now()->format('Ymd-His').'.xlsx')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
```

### new_string

```
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('exportAttendance')
                    ->label('Export Kehadiran')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn (Event $record) => (new EventAttendancesExport($record))
                        ->download('kehadiran-'.$record->id.'-'.now()->format('Ymd-His').'.xlsx')),
                Tables\Actions\DeleteAction::make(),
            ])
```

---

