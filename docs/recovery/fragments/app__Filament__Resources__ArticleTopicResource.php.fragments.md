# StrReplace fragments for `/home/sepetak.org/app/Filament/Resources/ArticleTopicResource.php`

Total edits captured in transcript: **13**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
                            Forms\Components\Select::make('article_type')
                                ->label('Tipe Artikel')
                                ->options([
                                    'essay' => 'Essay Akademik',
                                    'opinion' => 'Opini Mendalam',
                                    'scientific_review' => 'Kajian Ilmiah',
                                    'policy_analysis' => 'Analisis Kebijakan',
                                    'thinker_profile' => 'Profil Pemikiran',
                                    'historical_review' => 'Tinjauan Historis',
                                ])
```

### new_string

```
                            Forms\Components\Select::make('article_type')
                                ->label('Tipe Artikel')
                                ->options([
                                    'essay' => 'Essay Akademik',
                                    'opinion' => 'Opini Mendalam',
                                    'scientific_review' => 'Kajian Ilmiah',
                                    'policy_analysis' => 'Analisis Kebijakan',
                                    'thinker_profile' => 'Profil Pemikiran',
                                    'historical_review' => 'Tinjauan Historis',
                                    'member_guide' => 'Panduan ringkas anggota (AI praktis)',
                                ])
```

---

## Edit #2

### old_string

```
                Tables\Filters\SelectFilter::make('article_type')
                    ->label('Tipe')
                    ->options([
                        'essay' => 'Essay',
                        'opinion' => 'Opini',
                        'scientific_review' => 'Kajian Ilmiah',
                        'policy_analysis' => 'Analisis Kebijakan',
                        'thinker_profile' => 'Profil Pemikiran',
                        'historical_review' => 'Tinjauan Historis',
                    ]),
```

### new_string

```
                Tables\Filters\SelectFilter::make('article_type')
                    ->label('Tipe')
                    ->options([
                        'essay' => 'Essay',
                        'opinion' => 'Opini',
                        'scientific_review' => 'Kajian Ilmiah',
                        'policy_analysis' => 'Analisis Kebijakan',
                        'thinker_profile' => 'Profil Pemikiran',
                        'historical_review' => 'Tinjauan Historis',
                        'member_guide' => 'Panduan anggota',
                    ]),
```

---

## Edit #3

### old_string

```
                Tables\Columns\TextColumn::make('article_type')
                    ->label('Tipe')
                    ->badge()
                    ->color('info'),
```

### new_string

```
                Tables\Columns\TextColumn::make('article_type')
                    ->label('Tipe')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'member_guide' => 'Panduan anggota',
                        default => str_replace('_', ' ', $state),
                    }),
```

---

## Edit #4

### old_string

```
                        GenerateArticleJob::dispatch($record, null, 'manual');

                        Notification::make()
                            ->title('Artikel sedang diproses')
                            ->body("Topik \"{$record->title}\" dikirim ke antrian.")
```

### new_string

```
                        $pool = $record->pools()
                            ->where('article_pools.content_profile', 'member_practical')
                            ->where('article_pools.is_active', true)
                            ->orderBy('article_pools.id')
                            ->first()
                            ?? $record->pools()
                                ->where('article_pools.is_active', true)
                                ->orderBy('article_pools.id')
                                ->first();

                        GenerateArticleJob::dispatch($record, $pool, 'manual');

                        $poolNote = $pool ? " Pool: {$pool->name}." : '';

                        Notification::make()
                            ->title('Artikel sedang diproses')
                            ->body("Topik \"{$record->title}\" dikirim ke antrian.{$poolNote}")
```

---

## Edit #5

### old_string

```
                        $pool = $record->pools()
                            ->where('article_pools.content_profile', 'member_practical')
                            ->where('article_pools.is_active', true)
                            ->orderBy('article_pools.id')
                            ->first()
                            ?? $record->pools()
                                ->where('article_pools.is_active', true)
                                ->orderBy('article_pools.id')
                                ->first();

                        GenerateArticleJob::dispatch($record, $pool, 'manual');
```

### new_string

```
                        $pool = $record->pools()
                            ->where('article_pools.content_profile', 'member_practical')
                            ->where('article_pools.is_active', true)
                            ->orderBy('article_pools.id')
                            ->first()
                            ?? $record->pools()
                                ->where('article_pools.is_active', true)
                                ->orderBy('article_pools.id')
                                ->first();

                        if ($pool === null && ContentProfile::forArticleGeneration(null, $record)->isMemberPractical()) {
                            $pool = ArticlePool::query()
                                ->where('content_profile', 'member_practical')
                                ->where('is_active', true)
                                ->orderBy('id')
                                ->first();
                        }

                        GenerateArticleJob::dispatch($record, $pool, 'manual');
```

---

## Edit #6

### old_string

```
use App\Filament\Resources\ArticleTopicResource\Pages;
use App\Jobs\GenerateArticleJob;
use App\Models\ArticleTopic;
```

### new_string

```
use App\Filament\Resources\ArticleTopicResource\Pages;
use App\Jobs\GenerateArticleJob;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\ContentProfile;
```

---

## Edit #7

### old_string

```
                    Forms\Components\Tabs\Tab::make('Publikasi')
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                ->label('Kategori Default')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('tags')
```

### new_string

```
                    Forms\Components\Tabs\Tab::make('Publikasi')
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                ->label('Kategori Default')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('Untuk panduan anggota: pilih kategori setara «Panduan & Tips Anggota» (slug panduan-tips-anggota) bila tersedia.'),

                            Forms\Components\Select::make('pools')
                                ->label('Pool jadwal')
                                ->relationship(
                                    'pools',
                                    'name',
                                    fn (Builder $query) => $query->orderBy('name'),
                                )
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->columnSpanFull()
                                ->helperText('Hubungkan ke pool profil Ringkas praktis agar jadwal & log generasi konsisten. Bisa juga diatur dari Pool Jadwal → Topik terhubung.'),

                            Forms\Components\Select::make('tags')
```

---

## Edit #8

### old_string

```
                            Forms\Components\Select::make('article_type')
                                ->label('Tipe Artikel')
                                ->options([
                                    'essay' => 'Essay Akademik',
                                    'opinion' => 'Opini Mendalam',
                                    'scientific_review' => 'Kajian Ilmiah',
                                    'policy_analysis' => 'Analisis Kebijakan',
                                    'thinker_profile' => 'Profil Pemikiran',
                                    'historical_review' => 'Tinjauan Historis',
                                    'member_guide' => 'Panduan ringkas anggota (AI praktis)',
                                ])
                                ->required()
                                ->default('essay'),
```

### new_string

```
                            Forms\Components\Select::make('article_type')
                                ->label('Tipe Artikel')
                                ->options([
                                    'essay' => 'Essay Akademik',
                                    'opinion' => 'Opini Mendalam',
                                    'scientific_review' => 'Kajian Ilmiah',
                                    'policy_analysis' => 'Analisis Kebijakan',
                                    'thinker_profile' => 'Profil Pemikiran',
                                    'historical_review' => 'Tinjauan Historis',
                                    'member_guide' => 'Panduan ringkas anggota (AI praktis)',
                                ])
                                ->required()
                                ->default('essay')
                                ->helperText('Artikel harian/tips: pilih Panduan ringkas anggota dan hubungkan ke pool Ringkas praktis (tab Publikasi).'),
```

---

## Edit #9

### old_string

```
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
```

### new_string

```
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
```

---

## Edit #10

### old_string

```
->helperText('Untuk panduan anggota: pilih kategori setara «Panduan & Tips Anggota» (slug panduan-tips-anggota) bila tersedia.'),
```

### new_string

```
->helperText('Untuk panduan anggota: pilih kategori "Panduan & Tips Anggota" (slug panduan-tips-anggota) bila tersedia.'),
```

---

## Edit #11

### old_string

```
                    Forms\Components\Tabs\Tab::make('Publikasi')
                        ->schema([
                            Forms\Components\Select::make('category_id')
```

### new_string

```
                    Forms\Components\Tabs\Tab::make('Publikasi')
                        ->schema([
                            Forms\Components\Placeholder::make('publikasi_hint')
                                ->label('')
                                ->content('Kategori, pool jadwal, dan tag dipakai saat artikel dihasilkan dari topik ini. Pool ringkas praktis sebaiknya diisi agar jadwal & log konsisten.')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('category_id')
```

---

## Edit #12

### old_string

```
                    Forms\Components\Tabs\Tab::make('Publikasi')
                        ->schema([
                            Forms\Components\Placeholder::make('publikasi_hint')
                                ->label('')
                                ->content('Kategori, pool jadwal, dan tag dipakai saat artikel dihasilkan dari topik ini. Pool ringkas praktis sebaiknya diisi agar jadwal & log konsisten.')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('category_id')
```

### new_string

```
                    Forms\Components\Tabs\Tab::make('Publikasi')
                        ->schema([
                            Forms\Components\Select::make('category_id')
```

---

## Edit #13

### old_string

```
                            Forms\Components\Select::make('pools')
                                ->label('Pool jadwal')
                                ->relationship(
                                    'pools',
                                    'name',
                                    fn (Builder $query) => $query->orderBy('name'),
                                )
```

### new_string

```
                            Forms\Components\Select::make('pools')
                                ->label('Pool jadwal')
                                ->relationship(
                                    'pools',
                                    'name',
                                    // Hindari SELECT DISTINCT * di PG: kolom JSON `schedule_times` memicu error 42883.
                                    fn (Builder $query) => $query
                                        ->select(['article_pools.id', 'article_pools.name', 'article_pools.slug', 'article_pools.content_profile'])
                                        ->orderBy('article_pools.name'),
                                )
```

---

