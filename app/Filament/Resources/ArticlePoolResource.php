<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticlePoolResource\Pages;
use App\Models\ArticlePool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug((string) $state))),
                Forms\Components\TextInput::make('slug')->label('Slug')->required()->maxLength(255),
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
                    ->options(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan'])
                    ->default('daily')
                    ->required(),
                Forms\Components\TextInput::make('schedule_day')->label('Hari (0–6 atau 1–31)')->numeric(),
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
                Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                Forms\Components\Toggle::make('auto_publish')->label('Publikasi Otomatis')->default(false)
                    ->helperText('Jika aktif, artikel yang lolos validator akan langsung dipublikasikan.'),
                Forms\Components\Select::make('topics')
                    ->label('Topik Terkait')
                    ->relationship('topics', 'title')
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
                Tables\Columns\BadgeColumn::make('content_profile')->label('Profil')->colors([
                    'primary' => 'pillar',
                    'success' => 'member_practical',
                ]),
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
}
