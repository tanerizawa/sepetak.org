# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/MemberResource.php`

Total edits captured in transcript: **17**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
use App\Filament\Resources\MemberResource\Pages;
use App\Models\Address;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
```

### new_string

```
use App\Exports\MembersExport;
use App\Filament\Resources\MemberResource\Pages;
use App\Models\Address;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Response;
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
                    ->action(fn () => (new MembersExport())->download('anggota-sepetak-' . now()->format('Ymd-His') . '.xlsx')),

                Tables\Actions\Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->action(fn () => redirect()->to(route('admin.exports.members.pdf'))),
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
use Filament\Tables\Table;
use Illuminate\Support\Facades\Response;
```

### new_string

```
use Filament\Tables\Table;
```

---

## Edit #4

### old_string

```
                Tables\Columns\BadgeColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                        'other' => 'Lainnya',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'gray' => 'inactive',
                        'danger' => 'resigned',
                        'secondary' => 'deceased',
                    ]),
```

### new_string

```
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'male' => 'info',
                        'female' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'inactive' => 'gray',
                        'resigned' => 'danger',
                        'deceased' => 'secondary',
                        default => 'gray',
                    }),
```

---

## Edit #5

### old_string

```
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
```

### new_string

```
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('Foto')
                    ->circular()
                    ->size(42)
                    ->defaultImageUrl(url('/images/default-photo.png'))
                    ->getStateUsing(fn (Member $record) => $record->getFirstMediaUrl('photo') ?: null),

                Tables\Columns\TextColumn::make('member_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
```

---

## Edit #6

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
                    ->action(fn () => (new MembersExport)->download('anggota-sepetak-'.now()->format('Ymd-His').'.xlsx')),
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
                    ->action(fn () => (new MembersExport)->download('anggota-sepetak-'.now()->format('Ymd-His').'.xlsx')),
```

---

## Edit #7

### old_string

```
use App\Exports\MembersExport;
use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
```

### new_string

```
use App\Exports\MembersExport;
use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
```

---

## Edit #8

### old_string

```
    public static function getRelationManagers(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
```

### new_string

```
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Identitas Anggota')
                ->columns(2)
                ->schema([
                    Infolists\Components\ImageEntry::make('photo_url')
                        ->label('Foto')
                        ->circular()
                        ->size(120)
                        ->defaultImageUrl(url('/images/default-photo.png'))
                        ->getStateUsing(fn (Member $record) => $record->getFirstMediaUrl('photo') ?: null),
                    Infolists\Components\Group::make([
                        Infolists\Components\TextEntry::make('member_code')->label('Kode Anggota'),
                        Infolists\Components\TextEntry::make('full_name')->label('Nama Lengkap'),
                        Infolists\Components\TextEntry::make('gender')
                            ->label('Jenis Kelamin')
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'male' => 'Laki-laki',
                                'female' => 'Perempuan',
                                'other' => 'Lainnya',
                                default => '-',
                            }),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'pending' => 'warning',
                                'active' => 'success',
                                'inactive' => 'gray',
                                'resigned' => 'danger',
                                'deceased' => 'secondary',
                                default => 'gray',
                            }),
                    ]),
                ]),

            Infolists\Components\Section::make('Kontak & Alamat')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('phone')->label('Telepon')->default('-'),
                    Infolists\Components\TextEntry::make('email')->label('Email')->default('-'),
                    Infolists\Components\TextEntry::make('birth_place')->label('Tempat Lahir')->default('-'),
                    Infolists\Components\TextEntry::make('birth_date')->label('Tanggal Lahir')->date()->default('-'),
                    Infolists\Components\TextEntry::make('joined_at')->label('Bergabung')->date()->default('-'),
                    Infolists\Components\TextEntry::make('address.line_1')->label('Alamat')->default('-'),
                    Infolists\Components\TextEntry::make('address.village')->label('Desa')->default('-'),
                    Infolists\Components\TextEntry::make('address.district')->label('Kecamatan')->default('-'),
                    Infolists\Components\TextEntry::make('address.regency')->label('Kabupaten')->default('-'),
                    Infolists\Components\TextEntry::make('address.province')->label('Provinsi')->default('-'),
                ]),

            Infolists\Components\Section::make('Galeri Dokumen')
                ->description('Arsip berkas pendukung anggota (KTP, ijazah, surat dukungan, dll.).')
                ->schema([
                    Infolists\Components\View::make('filament.infolists.components.media-gallery')
                        ->viewData(fn (Member $record) => [
                            'items' => $record->getMedia('documents'),
                            'emptyText' => 'Belum ada dokumen yang diunggah.',
                        ]),
                ])
                ->collapsible(),
        ]);
    }

    public static function getRelationManagers(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
```

---

## Edit #9

### old_string

```
                    Infolists\Components\TextEntry::make('phone')->label('Telepon')->default('-'),
                    Infolists\Components\TextEntry::make('email')->label('Email')->default('-'),
                    Infolists\Components\TextEntry::make('birth_place')->label('Tempat Lahir')->default('-'),
                    Infolists\Components\TextEntry::make('birth_date')->label('Tanggal Lahir')->date()->default('-'),
                    Infolists\Components\TextEntry::make('joined_at')->label('Bergabung')->date()->default('-'),
                    Infolists\Components\TextEntry::make('address.line_1')->label('Alamat')->default('-'),
                    Infolists\Components\TextEntry::make('address.village')->label('Desa')->default('-'),
                    Infolists\Components\TextEntry::make('address.district')->label('Kecamatan')->default('-'),
                    Infolists\Components\TextEntry::make('address.regency')->label('Kabupaten')->default('-'),
                    Infolists\Components\TextEntry::make('address.province')->label('Provinsi')->default('-'),
```

### new_string

```
                    Infolists\Components\TextEntry::make('phone')->label('Telepon')->placeholder('-'),
                    Infolists\Components\TextEntry::make('email')->label('Email')->placeholder('-'),
                    Infolists\Components\TextEntry::make('birth_place')->label('Tempat Lahir')->placeholder('-'),
                    Infolists\Components\TextEntry::make('birth_date')->label('Tanggal Lahir')->date()->placeholder('-'),
                    Infolists\Components\TextEntry::make('joined_at')->label('Bergabung')->date()->placeholder('-'),
                    Infolists\Components\TextEntry::make('address.line_1')->label('Alamat')->placeholder('-'),
                    Infolists\Components\TextEntry::make('address.village')->label('Desa')->placeholder('-'),
                    Infolists\Components\TextEntry::make('address.district')->label('Kecamatan')->placeholder('-'),
                    Infolists\Components\TextEntry::make('address.regency')->label('Kabupaten')->placeholder('-'),
                    Infolists\Components\TextEntry::make('address.province')->label('Provinsi')->placeholder('-'),
```

---

## Edit #10

### old_string

```
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'inactive' => 'gray',
                        'resigned' => 'danger',
                        'deceased' => 'secondary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('phone')
```

### new_string

```
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'inactive' => 'gray',
                        'resigned' => 'danger',
                        'deceased' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('phone')
```

---

## Edit #11

### old_string

```
                            ->color(fn (string $state) => match ($state) {
                                'pending' => 'warning',
                                'active' => 'success',
                                'inactive' => 'gray',
                                'resigned' => 'danger',
                                'deceased' => 'secondary',
                                default => 'gray',
                            }),
```

### new_string

```
                            ->color(fn (string $state) => match ($state) {
                                'pending' => 'warning',
                                'active' => 'success',
                                'inactive' => 'gray',
                                'resigned' => 'danger',
                                'deceased' => 'gray',
                                default => 'gray',
                            }),
```

---

## Edit #12

### old_string

```
                    Forms\Components\TextInput::make('phone')
                        ->label('Telepon')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\TextInput::make('email')
```

### new_string

```
                    Forms\Components\TextInput::make('phone')
                        ->label('Telepon')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\Toggle::make('whatsapp_notifications')
                        ->label('Terima broadcast WhatsApp')
                        ->helperText('Jika aktif, nomor di atas dapat dipakai sekretariat untuk pengumuman via WA (WAHA), sesuai kebijakan organisasi.')
                        ->default(true),

                    Forms\Components\TextInput::make('email')
```

---

## Edit #13

### old_string

```
                    Forms\Components\Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->options([
                            'male' => 'Laki-laki',
                            'female' => 'Perempuan',
                            'other' => 'Lainnya',
                        ]),
```

### new_string

```
                    Forms\Components\Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->options([
                            'male' => 'Laki-laki',
                            'female' => 'Perempuan',
                            'other' => 'Lainnya',
                        ])
                        ->required()
                        ->default('male'),
```

---

## Edit #14

### old_string

```
                    Forms\Components\TextInput::make('full_name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),
```

### new_string

```
                    Forms\Components\TextInput::make('full_name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(150),
```

---

## Edit #15

### old_string

```
                    Forms\Components\TextInput::make('address_line_1')
                        ->label('Alamat')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('address_village')
                        ->label('Desa/Kelurahan')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('address_district')
                        ->label('Kecamatan')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('address_regency')
                        ->label('Kabupaten/Kota')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('address_province')
                        ->label('Provinsi')
                        ->maxLength(255),
```

### new_string

```
                    Forms\Components\TextInput::make('address_line_1')
                        ->label('Alamat')
                        ->maxLength(200),

                    Forms\Components\TextInput::make('address_village')
                        ->label('Desa/Kelurahan')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('address_district')
                        ->label('Kecamatan')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('address_regency')
                        ->label('Kabupaten/Kota')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('address_province')
                        ->label('Provinsi')
                        ->maxLength(100),
```

---

## Edit #16

### old_string

```
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),
```

### new_string

```
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(150),
```

---

## Edit #17

### old_string

```
                    Forms\Components\TextInput::make('birth_place')
                        ->label('Tempat Lahir')
                        ->maxLength(255),
```

### new_string

```
                    Forms\Components\TextInput::make('birth_place')
                        ->label('Tempat Lahir')
                        ->maxLength(100),
```

---

