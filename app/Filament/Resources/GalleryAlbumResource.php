<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryAlbumResource\Pages;
use App\Models\GalleryAlbum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GalleryAlbumResource extends Resource
{
    protected static ?string $model = GalleryAlbum::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Album Galeri';

    protected static ?string $pluralModelLabel = 'Album Galeri';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Album')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Album')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                            $set('slug', Str::slug($state));
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(GalleryAlbum::class, 'slug', ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\DatePicker::make('event_date')
                        ->label('Tanggal Kegiatan'),

                    Forms\Components\TextInput::make('location')
                        ->label('Lokasi')
                        ->maxLength(255),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('album_cover')
                        ->label('Cover Album')
                        ->collection('album_cover')
                        ->image()
                        ->columnSpanFull(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Dipublikasikan',
                            'archived' => 'Diarsipkan',
                        ])
                        ->required()
                        ->default('draft'),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Tanggal Publikasi'),

                    Forms\Components\Select::make('author_id')
                        ->label('Oleh')
                        ->relationship('author', 'name')
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(2),

            Forms\Components\Section::make('Foto & Video')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->label('')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('type')
                                ->label('Tipe')
                                ->options([
                                    'photo' => 'Foto',
                                    'video' => 'Video',
                                ])
                                ->default('photo')
                                ->required()
                                ->live(),

                            Forms\Components\SpatieMediaLibraryFileUpload::make('gallery_photo')
                                ->label('File Foto')
                                ->collection('gallery_photo')
                                ->image()
                                ->visible(fn (Forms\Get $get) => $get('type') === 'photo')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('video_url')
                                ->label('URL Video (YouTube/Vimeo)')
                                ->url()
                                ->visible(fn (Forms\Get $get) => $get('type') === 'video')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('title')
                                ->label('Judul')
                                ->maxLength(255),

                            Forms\Components\Textarea::make('caption')
                                ->label('Keterangan')
                                ->rows(2),

                            Forms\Components\TextInput::make('credit')
                                ->label('Kredit Foto/Video')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(2)
                        ->orderColumn('sort_order')
                        ->reorderable()
                        ->collapsible()
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string => ($state['title'] ?? null) ?: (($state['type'] ?? 'photo') === 'video' ? 'Video' : 'Foto'))
                        ->defaultItems(0)
                        ->addActionLabel('Tambah Foto/Video'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('album_cover')
                    ->label('')
                    ->collection('album_cover')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Item')
                    ->counts('items')
                    ->suffix(' media')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'warning' => 'archived',
                    ]),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasikan')
                    ->dateTime('d M Y · H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Dipublikasikan',
                        'archived' => 'Diarsipkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('publish')
                    ->label('Publikasikan')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn ($records) => $records->each(fn ($r) => $r->update([
                        'status' => 'published',
                        'published_at' => $r->published_at ?? now(),
                    ])))
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleryAlbums::route('/'),
            'create' => Pages\CreateGalleryAlbum::route('/create'),
            'edit' => Pages\EditGalleryAlbum::route('/{record}/edit'),
        ];
    }
}
