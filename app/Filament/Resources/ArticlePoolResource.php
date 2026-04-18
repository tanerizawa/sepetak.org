<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticlePoolResource\Pages;
use App\Filament\Resources\ArticlePoolResource\Support\PoolArticleGenerationNotification;
use App\Models\ArticlePool;
use App\Services\ManualPoolArticleGeneration;
use Closure;
use Filament\Actions as FilamentActions;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArticlePoolResource extends Resource
{
    protected static ?string $model = ArticlePool::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Artikel Otomatis';

    protected static ?string $modelLabel = 'Pool Artikel';

    protected static ?string $pluralModelLabel = 'Pool Artikel';

    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Pool')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                        // Hanya isi slug otomatis saat slug masih kosong (buat baru). Pada edit, jangan
                        // menimpa slug saat blur nama — bisa memutus slug stabil yang dipakai penjadwal/CLI.
                        if (filled($get('slug'))) {
                            return;
                        }
                        $set('slug', Str::slug((string) $state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Setelah pool dipakai di produksi, ubah slug hanya bila Anda mengarahkan ulang penjadwal atau skrip yang memakai slug ini.'),
                Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                Forms\Components\Select::make('content_profile')
                    ->label('Profil Konten')
                    ->options([
                        'pillar' => 'Pilar (akademik panjang)',
                        'member_practical' => 'Panduan Praktis Anggota',
                    ])
                    ->default('pillar')
                    ->required()
                    ->helperText('Menentukan gaya prompt, target panjang, dan aturan validasi.'),
            ])->columns(2),

            Forms\Components\Section::make('Jadwal')->schema([
                Forms\Components\Select::make('schedule_frequency')
                    ->label('Frekuensi')
                    ->options([
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'biweekly' => 'Dua mingguan',
                        'monthly' => 'Bulanan',
                    ])
                    ->default('daily')
                    ->required(),
                Forms\Components\TextInput::make('schedule_day')
                    ->label('Hari jadwal')
                    ->helperText('Mingguan/dua mingguan: nama hari (mis. monday) atau angka 0–6 (0=Minggu). Bulanan: tanggal 1–31.'),
                Forms\Components\TimePicker::make('schedule_time')->label('Waktu (satu slot)')->seconds(false),
                Forms\Components\TagsInput::make('schedule_times')
                    ->label('Slot Jam Harian (HH:MM)')
                    ->placeholder('04:45, 12:10, 15:20, 18:05, 19:25')
                    ->helperText('Isi beberapa slot untuk frekuensi harian. Kosongkan jika memakai slot tunggal di atas.')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('articles_per_run')
                    ->label('Artikel per Run')
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
            ])->columns(2),

            Forms\Components\Section::make('Perilaku')->schema([
                Toggle::make('is_active')->label('Aktif')->default(true),
                Toggle::make('auto_publish')->label('Publikasi Otomatis')->default(false)
                    ->helperText('Jika aktif, artikel yang lolos validator akan langsung dipublikasikan.'),
                Forms\Components\Select::make('topics')
                    ->label('Topik Terkait')
                    ->relationship(
                        'topics',
                        'title',
                        fn (Builder $query): Builder => $query->select(['article_topics.id', 'article_topics.title'])
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('content_profile')
                    ->label('Profil')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pillar' => 'Pilar',
                        'member_practical' => 'Panduan Praktis',
                        default => $state ?: '-',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'pillar' => 'primary',
                        'member_practical' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('schedule_frequency')->label('Frekuensi')->badge(),
                Tables\Columns\TextColumn::make('articles_per_run')->label('Per Run')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\IconColumn::make('auto_publish')->label('Auto Publish')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
                Tables\Filters\SelectFilter::make('content_profile')->options([
                    'pillar' => 'Pilar',
                    'member_practical' => 'Panduan Praktis',
                ]),
            ])
            ->actions([
                static::makeGenerateArticleTableAction(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticlePools::route('/'),
            'create' => Pages\CreateArticlePool::route('/create'),
            'edit' => Pages\EditArticlePool::route('/{record}/edit'),
        ];
    }

    public static function makeGenerateArticleTableAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('generate_article_now')
            ->label('Generate sekarang')
            ->icon('heroicon-o-sparkles')
            ->color('success')
            ->modalHeading('Generate artikel otomatis')
            ->modalDescription('Menjalankan AI untuk pool ini sekarang (satu run = jumlah “Artikel per Run”). Topik dipilih otomatis.')
            ->modalSubmitActionLabel('Jalankan')
            ->form([
                Toggle::make('ignore_daily_cap')
                    ->label('Abaikan batas artikel per hari')
                    ->helperText('Setara `--force` pada Artisan. Hanya dipakai jika `ARTICLE_MAX_PER_DAY` sudah tercapai.')
                    ->default(false),
            ])
            ->action(function (ArticlePool $record, array $data): void {
                $result = app(ManualPoolArticleGeneration::class)->run(
                    $record,
                    (bool) ($data['ignore_daily_cap'] ?? false),
                );
                PoolArticleGenerationNotification::send($result);
            });
    }

    public static function makeGenerateArticleHeaderAction(Closure $resolvePool): FilamentActions\Action
    {
        return FilamentActions\Action::make('generate_article_now')
            ->label('Generate artikel sekarang')
            ->icon('heroicon-o-sparkles')
            ->color('success')
            ->modalHeading('Generate artikel otomatis')
            ->modalDescription('Menjalankan AI untuk pool ini sekarang (satu run = jumlah “Artikel per Run”). Topik dipilih otomatis.')
            ->modalSubmitActionLabel('Jalankan')
            ->form([
                Toggle::make('ignore_daily_cap')
                    ->label('Abaikan batas artikel per hari')
                    ->helperText('Setara `--force` pada Artisan. Hanya dipakai jika `ARTICLE_MAX_PER_DAY` sudah tercapai.')
                    ->default(false),
            ])
            ->action(function (array $data) use ($resolvePool): void {
                $pool = $resolvePool();
                if (! $pool instanceof ArticlePool) {
                    return;
                }
                $result = app(ManualPoolArticleGeneration::class)->run(
                    $pool,
                    (bool) ($data['ignore_daily_cap'] ?? false),
                );
                PoolArticleGenerationNotification::send($result);
            });
    }
}
