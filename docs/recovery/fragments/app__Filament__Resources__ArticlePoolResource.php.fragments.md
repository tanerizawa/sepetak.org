# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/ArticlePoolResource.php`

Total edits captured in transcript: **11**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
                Tables\Columns\TextColumn::make('schedule_time')
                    ->label('Waktu')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('articles_per_run')
```

### new_string

```
                Tables\Columns\TextColumn::make('schedule_time')
                    ->label('Waktu')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('schedule_times')
                    ->label('Slot jam')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn ($state) => is_array($state) && $state !== [] ? implode(', ', $state) : '—'),

                Tables\Columns\TextColumn::make('content_profile')
                    ->label('Profil')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'member_practical' => 'Praktis',
                        default => 'Pillar',
                    }),

                Tables\Columns\TextColumn::make('articles_per_run')
```

---

## Edit #2

### old_string

```
                    Forms\Components\TimePicker::make('schedule_time')
                        ->label('Waktu (WIB)')
                        ->required()
                        ->default('07:00')
                        ->seconds(false),

                    Forms\Components\TextInput::make('articles_per_run')
                        ->label('Artikel per Jadwal')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(5)
                        ->default(1),
                ])->columns(2),

            Forms\Components\Section::make('Konfigurasi')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    Forms\Components\Toggle::make('auto_publish')
                        ->label('Auto Publish')
                        ->helperText('⚠️ Jika aktif, artikel langsung dipublikasikan tanpa review.')
                        ->default(false),
```

### new_string

```
                    Forms\Components\TimePicker::make('schedule_time')
                        ->label('Waktu (satu slot, WIB)')
                        ->required()
                        ->default('07:00')
                        ->seconds(false)
                        ->helperText('Digunakan jika "Slot jam harian" kosong.'),

                    Forms\Components\TagsInput::make('schedule_times')
                        ->label('Slot jam harian (WIB)')
                        ->placeholder('04:45')
                        ->helperText('Isi jam HH:MM untuk beberapa kali sehari (contoh 5× kisaran shalat: 04:45, 12:10, 15:20, 18:05, 19:25). Kosongkan untuk hanya memakai satu jam di atas. Wajib frekuensi Harian.')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('articles_per_run')
                        ->label('Artikel per Jadwal')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(10)
                        ->default(1),
                ])->columns(2),

            Forms\Components\Section::make('Konfigurasi')
                ->schema([
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    Forms\Components\Toggle::make('auto_publish')
                        ->label('Auto Publish')
                        ->helperText('⚠️ Jika aktif, artikel langsung dipublikasikan tanpa review.')
                        ->default(false),
```

---

## Edit #3

### old_string

```
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required(),
```

### new_string

```
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required()
                        ->helperText('**Praktis:** hubungkan hanya topik bertipe *Panduan ringkas anggota* (dan/atau kategori panduan). **Pillar:** esai/kajian panjang. Tidak perlu menghapus pool lama — nonaktifkan atau ubah profil; duplikat pool aktif untuk jenis berbeda membingungkan jadwal.'),
```

---

## Edit #4

### old_string

```
                        ->helperText('**Praktis:** hubungkan hanya topik bertipe *Panduan ringkas anggota* (dan/atau kategori panduan). **Pillar:** esai/kajian panjang. Tidak perlu menghapus pool lama — nonaktifkan atau ubah profil; duplikat pool aktif untuk jenis berbeda membingungkan jadwal.'),
```

### new_string

```
                        ->helperText('Praktis: hubungkan topik bertipe Panduan ringkas anggota (dan kategori panduan bila dipakai). Pillar: esai/kajian panjang. Pool lama tidak wajib dihapus — nonaktifkan atau ubah profil; hindari banyak pool aktif overlap untuk jenis berbeda agar jadwal konsisten.'),
```

---

## Edit #5

### old_string

```
                    Forms\Components\Select::make('topics')
                        ->label('Topik Terhubung')
                        ->relationship(
                            'topics',
                            'title',
                            fn (Builder $query) => $query->select(['article_topics.id', 'article_topics.title'])->orderBy('title'),
                        )
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
```

### new_string

```
                    Forms\Components\Select::make('topics')
                        ->label('Topik terhubung')
                        ->relationship(
                            'topics',
                            'title',
                            fn (Builder $query) => $query->select(['article_topics.id', 'article_topics.title'])->orderBy('title'),
                        )
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->helperText('Untuk pool Ringkas praktis: utamakan topik bertipe «Panduan ringkas anggota» (dan kategori panduan bila dipakai). Topik yang sama bisa diatur juga dari menu Topik Artikel → Pool jadwal.')
                        ->columnSpanFull(),
```

---

## Edit #6

### old_string

```
            Forms\Components\Section::make('Konfigurasi')
                ->schema([
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required()
                        ->helperText('Praktis: hubungkan topik bertipe Panduan ringkas anggota (dan kategori panduan bila dipakai). Pillar: esai/kajian panjang. Pool lama tidak wajib dihapus — nonaktifkan atau ubah profil; hindari banyak pool aktif overlap untuk jenis berbeda agar jadwal konsisten.'),
```

### new_string

```
            Forms\Components\Section::make('Konfigurasi')
                ->description('Ringkas praktis = prompt & validasi artikel ringgan anggota. Pillar = esai/kajian panjang. Pool lama jangan dihapus sembarangan: nonaktifkan atau ubah profil; hindari banyak pool aktif yang overlap jam.')
                ->schema([
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required()
                        ->live(),
```

---

## Edit #7

### old_string

```
                    Forms\Components\TimePicker::make('schedule_time')
                        ->label('Waktu (satu slot, WIB)')
                        ->required()
                        ->default('07:00')
                        ->seconds(false)
                        ->helperText('Digunakan jika "Slot jam harian" kosong.'),

                    Forms\Components\TagsInput::make('schedule_times')
                        ->label('Slot jam harian (WIB)')
                        ->placeholder('04:45')
                        ->helperText('Isi jam HH:MM untuk beberapa kali sehari (contoh 5× kisaran shalat: 04:45, 12:10, 15:20, 18:05, 19:25). Kosongkan untuk hanya memakai satu jam di atas. Wajib frekuensi Harian.')
                        ->columnSpanFull(),
```

### new_string

```
                    Forms\Components\TimePicker::make('schedule_time')
                        ->label('Waktu (satu slot, WIB)')
                        ->required()
                        ->default('07:00')
                        ->seconds(false)
                        ->helperText('Dipakai bila slot harian kosong.'),

                    Forms\Components\TagsInput::make('schedule_times')
                        ->label('Slot jam harian (WIB)')
                        ->placeholder('04:45')
                        ->helperText('Satu tag = satu HH:MM. Contoh lima kali sehari: 04:45, 12:10, 15:20, 18:05, 19:25.')
                        ->columnSpanFull(),
```

---

## Edit #8

### old_string

```
            Forms\Components\Section::make('Jadwal')
                ->schema([
                    Forms\Components\Select::make('schedule_frequency')
```

### new_string

```
            Forms\Components\Section::make('Jadwal')
                ->description('Beberapa slot jam hanya didukung jika frekuensi Harian. Setiap slot format HH:MM (contoh 04:45). Kosongkan slot untuk memakai satu waktu di bawah (Mingguan/Bulanan diperbolehkan).')
                ->schema([
                    Forms\Components\Select::make('schedule_frequency')
```

---

## Edit #9

### old_string

```
->description('Ringkas praktis = prompt & validasi artikel ringgan anggota. Pillar = esai/kajian panjang. Pool lama jangan dihapus sembarangan: nonaktifkan atau ubah profil; hindari banyak pool aktif yang overlap jam.')
```

### new_string

```
->description('Ringkas praktis = prompt & validasi artikel ringan anggota. Pillar = esai/kajian panjang. Pool lama jangan dihapus sembarangan: nonaktifkan atau ubah profil; hindari banyak pool aktif yang overlap jam.')
```

---

## Edit #10

### old_string

```
                        ->helperText('Untuk pool Ringkas praktis: utamakan topik bertipe «Panduan ringkas anggota» (dan kategori panduan bila dipakai). Topik yang sama bisa diatur juga dari menu Topik Artikel → Pool jadwal.')
                        ->columnSpanFull(),
```

### new_string

```
                        ->helperText('Pasang topik yang sesuai profil di atas (panduan anggota vs pillar). Dapat juga diubah dari Topik Artikel → Pool jadwal.')
                        ->columnSpanFull(),
```

---

## Edit #11

### old_string

```
            Forms\Components\Section::make('Konfigurasi')
                ->schema([
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required()
                        ->helperText('Praktis: hubungkan topik bertipe Panduan ringkas anggota (dan kategori panduan bila dipakai). Pillar: esai/kajian panjang. Pool lama tidak wajib dihapus — nonaktifkan atau ubah profil; hindari banyak pool aktif overlap untuk jenis berbeda agar jadwal konsisten.'),

                    Forms\Components\Toggle::make('is_active')
```

### new_string

```
            Forms\Components\Section::make('Konfigurasi')
                ->description('Ringkas praktis = prompt & validasi artikel ringan anggota. Pillar = esai/kajian panjang. Pool lama: nonaktifkan atau ubah profil; hindari banyak pool aktif overlap jam.')
                ->schema([
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required()
                        ->live(),

                    Forms\Components\Toggle::make('is_active')
```

---

