# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource.php`

Total edits captured in transcript: **9**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
use App\Filament\Resources\AgrarianCaseResource\Pages;
use App\Filament\Resources\AgrarianCaseResource\RelationManagers;
use App\Models\AgrarianCase;
use App\Models\User;
```

### new_string

```
use App\Exports\AgrarianCasesExport;
use App\Filament\Resources\AgrarianCaseResource\Pages;
use App\Filament\Resources\AgrarianCaseResource\RelationManagers;
use App\Models\AgrarianCase;
use App\Models\User;
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

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\PartiesRelationManager::class,
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
                    ->action(fn () => (new AgrarianCasesExport())->download('kasus-agraria-sepetak-' . now()->format('Ymd-His') . '.xlsx')),

                Tables\Actions\Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->action(fn () => redirect()->to(route('admin.exports.agrarian-cases.pdf'))),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\PartiesRelationManager::class,
```

---

## Edit #3

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

                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioritas')
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
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

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    }),
```

---

## Edit #4

### old_string

```
                Tables\Columns\TextColumn::make('leadUser.name')
                    ->label('Penanggung Jawab')
                    ->sortable(),
            ])
```

### new_string

```
                Tables\Columns\TextColumn::make('leadUser.name')
                    ->label('Penanggung Jawab')
                    ->sortable(),

                Tables\Columns\TextColumn::make('attachments_count')
                    ->label('Lampiran')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn (AgrarianCase $record) => $record->getMedia('attachments')->count()),
            ])
```

---

## Edit #5

### old_string

```
                    Forms\Components\Select::make('lead_user_id')
                        ->label('Penanggung Jawab')
                        ->relationship('leadUser', 'name')
                        ->searchable()
                        ->preload(),
                ])
                ->columns(2),
        ]);
    }
```

### new_string

```
                    Forms\Components\Select::make('lead_user_id')
                        ->label('Penanggung Jawab')
                        ->relationship('leadUser', 'name')
                        ->searchable()
                        ->preload(),

                    Forms\Components\FileUpload::make('attachments_upload')
                        ->label('Lampiran Tambahan (foto, dokumen, dll.)')
                        ->multiple()
                        ->disk('public')
                        ->directory('cases/attachments')
                        ->dehydrated(false)
                        ->columnSpanFull()
                        ->helperText('Upload di sini akan masuk ke media library kasus (pembanding riwayat). Gunakan RelationManager "Berkas" untuk dokumen formal bernama.'),
                ])
                ->columns(2),
        ]);
    }
```

---

## Edit #6

### old_string

```
use App\Exports\AgrarianCasesExport;
use App\Filament\Resources\AgrarianCaseResource\Pages;
use App\Filament\Resources\AgrarianCaseResource\RelationManagers;
use App\Models\AgrarianCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
```

### new_string

```
use App\Exports\AgrarianCasesExport;
use App\Filament\Resources\AgrarianCaseResource\Pages;
use App\Filament\Resources\AgrarianCaseResource\RelationManagers;
use App\Models\AgrarianCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->action(fn () => (new AgrarianCasesExport)->download('kasus-agraria-sepetak-'.now()->format('Ymd-His').'.xlsx')),
```

### new_string

```
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn () => (new AgrarianCasesExport)->download('kasus-agraria-sepetak-'.now()->format('Ymd-His').'.xlsx')),
```

---

## Edit #8

### old_string

```
    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\PartiesRelationManager::class,
            RelationManagers\UpdatesRelationManager::class,
            RelationManagers\FilesRelationManager::class,
        ];
    }
```

### new_string

```
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Ringkasan Kasus')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('case_code')->label('Kode Kasus'),
                    Infolists\Components\TextEntry::make('title')->label('Judul')->columnSpan(2),
                    Infolists\Components\TextEntry::make('status')
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
                    Infolists\Components\TextEntry::make('priority')
                        ->label('Prioritas')
                        ->badge()
                        ->color(fn (string $state) => match ($state) {
                            'low' => 'gray',
                            'medium' => 'info',
                            'high' => 'warning',
                            'urgent' => 'danger',
                            default => 'gray',
                        }),
                    Infolists\Components\TextEntry::make('start_date')->label('Tanggal Mulai')->date()->default('-'),
                    Infolists\Components\TextEntry::make('location_text')->label('Lokasi')->default('-'),
                    Infolists\Components\TextEntry::make('leadUser.name')->label('Penanggung Jawab')->default('-'),
                    Infolists\Components\TextEntry::make('summary')
                        ->label('Ringkasan')
                        ->columnSpanFull()
                        ->default('-'),
                    Infolists\Components\TextEntry::make('description')
                        ->label('Deskripsi')
                        ->html()
                        ->columnSpanFull()
                        ->default('-'),
                ]),

            Infolists\Components\Section::make('Galeri Lampiran')
                ->description('Foto, surat, dan dokumen pendukung kasus dari media library.')
                ->schema([
                    Infolists\Components\View::make('filament.infolists.components.media-gallery')
                        ->viewData(fn (AgrarianCase $record) => [
                            'items' => $record->getMedia('attachments'),
                            'emptyText' => 'Belum ada lampiran.',
                        ]),
                ])
                ->collapsible(),
        ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\PartiesRelationManager::class,
            RelationManagers\UpdatesRelationManager::class,
            RelationManagers\FilesRelationManager::class,
        ];
    }
```

---

## Edit #9

### old_string

```
                    Infolists\Components\TextEntry::make('start_date')->label('Tanggal Mulai')->date()->default('-'),
                    Infolists\Components\TextEntry::make('location_text')->label('Lokasi')->default('-'),
                    Infolists\Components\TextEntry::make('leadUser.name')->label('Penanggung Jawab')->default('-'),
                    Infolists\Components\TextEntry::make('summary')
                        ->label('Ringkasan')
                        ->columnSpanFull()
                        ->default('-'),
                    Infolists\Components\TextEntry::make('description')
                        ->label('Deskripsi')
                        ->html()
                        ->columnSpanFull()
                        ->default('-'),
```

### new_string

```
                    Infolists\Components\TextEntry::make('start_date')->label('Tanggal Mulai')->date()->placeholder('-'),
                    Infolists\Components\TextEntry::make('location_text')->label('Lokasi')->placeholder('-'),
                    Infolists\Components\TextEntry::make('leadUser.name')->label('Penanggung Jawab')->placeholder('-'),
                    Infolists\Components\TextEntry::make('summary')
                        ->label('Ringkasan')
                        ->columnSpanFull()
                        ->placeholder('-'),
                    Infolists\Components\TextEntry::make('description')
                        ->label('Deskripsi')
                        ->html()
                        ->columnSpanFull()
                        ->placeholder('-'),
```

---

