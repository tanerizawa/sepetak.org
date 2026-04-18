<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleTopicResource\Pages;
use App\Models\ArticleTopic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticleTopicResource extends Resource
{
    protected static ?string $model = ArticleTopic::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationGroup = 'Artikel Otomatis';

    protected static ?string $modelLabel = 'Topik Artikel';

    protected static ?string $pluralModelLabel = 'Topik Artikel';

    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Topik')->schema([
                Forms\Components\TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('slug')->label('Slug')->maxLength(255)->helperText('Dibuat otomatis dari judul bila kosong.'),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('article_type')
                    ->label('Jenis Artikel')
                    ->options([
                        'pillar' => 'Pilar (akademik panjang)',
                        'member_guide' => 'Panduan Anggota (praktis)',
                        'news' => 'Berita',
                        'opinion' => 'Opini',
                    ])
                    ->default('pillar')
                    ->required(),
                Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('thinking_framework')->label('Kerangka Berpikir')->rows(4)->columnSpanFull(),
                Forms\Components\KeyValue::make('key_references')->label('Referensi Kunci')->columnSpanFull(),
                Forms\Components\Textarea::make('prompt_template')->label('Template Prompt (opsional)')->rows(4)->columnSpanFull(),
                Forms\Components\TextInput::make('weight')->label('Bobot')->numeric()->default(1)->minValue(1),
                Forms\Components\TextInput::make('max_uses')->label('Maks Pakai (kosong = tak terbatas)')->numeric()->minValue(1),
                Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('article_type')->label('Jenis')->badge(),
                Tables\Columns\TextColumn::make('weight')->label('Bobot')->sortable(),
                Tables\Columns\TextColumn::make('times_used')->label('Terpakai')->sortable(),
                Tables\Columns\TextColumn::make('max_uses')->label('Maks')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
                Tables\Filters\SelectFilter::make('article_type')->options([
                    'pillar' => 'Pilar',
                    'member_guide' => 'Panduan Anggota',
                    'news' => 'Berita',
                    'opinion' => 'Opini',
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
            ->defaultSort('weight', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleTopics::route('/'),
            'create' => Pages\CreateArticleTopic::route('/create'),
            'edit' => Pages\EditArticleTopic::route('/{record}/edit'),
        ];
    }
}
