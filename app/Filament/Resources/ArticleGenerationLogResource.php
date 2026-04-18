<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleGenerationLogResource\Pages;
use App\Jobs\GenerateArticleJob;
use App\Models\ArticleGenerationLog;
use App\Services\ArticleImageService;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleGenerationLogResource extends Resource
{
    protected static ?string $model = ArticleGenerationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Artikel Otomatis';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Log Generasi';

    protected static ?string $pluralModelLabel = 'Log Generasi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('topic.title')
                    ->label('Topik')
                    ->limit(40)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('pool.name')
                    ->label('Pool')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'completed' => 'success',
                        'generating', 'queued' => 'info',
                        'failed' => 'danger',
                        'rejected' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('ai_model')
                    ->label('Model')
                    ->limit(30)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('tokens_used')
                    ->label('Tokens')
                    ->numeric()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('generation_time_ms')
                    ->label('Durasi')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1000, 1).'s' : '—')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('triggered_by')
                    ->label('Trigger')
                    ->badge()
                    ->color(fn (string $state) => $state === 'manual' ? 'info' : 'gray'),

                Tables\Columns\TextColumn::make('post.title')
                    ->label('Artikel')
                    ->limit(30)
                    ->url(fn ($record) => $record->post_id
                        ? PostResource::getUrl('edit', ['record' => $record->post_id])
                        : null)
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'queued' => 'Queued',
                        'generating' => 'Generating',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('triggered_by')
                    ->label('Trigger')
                    ->options([
                        'scheduler' => 'Scheduler',
                        'manual' => 'Manual',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_failed')
                    ->label('Tandai Gagal')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ArticleGenerationLog $record) => in_array($record->status, ['queued', 'generating']))
                    ->requiresConfirmation()
                    ->action(function (ArticleGenerationLog $record) {
                        $record->markFailed('Ditandai gagal secara manual oleh admin');
                        Notification::make()->title('Log ditandai sebagai gagal')->success()->send();
                    }),
                Tables\Actions\Action::make('retry')
                    ->label('Coba Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (ArticleGenerationLog $record) => in_array($record->status, ['failed', 'rejected']) && $record->topic)
                    ->requiresConfirmation()
                    ->modalHeading('Ulangi generasi artikel?')
                    ->modalDescription('Job baru akan di-dispatch untuk topik yang sama. Log ini tetap sebagai riwayat.')
                    ->action(function (ArticleGenerationLog $record) {
                        if (! $record->topic) {
                            Notification::make()->title('Topik sudah dihapus — tidak dapat retry')->danger()->send();

                            return;
                        }
                        GenerateArticleJob::dispatch($record->topic, $record->pool, 'manual');
                        Notification::make()->title('Job retry di-dispatch')->success()->send();
                    }),
                Tables\Actions\Action::make('attach_cover')
                    ->label('Pasang Cover')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->visible(fn (ArticleGenerationLog $record) => $record->post_id && $record->post && ! $record->post->getFirstMediaUrl('cover'))
                    ->requiresConfirmation()
                    ->action(function (ArticleGenerationLog $record) {
                        $service = app(ArticleImageService::class);
                        $attached = $service->attachCoverImage($record->post);
                        if ($attached) {
                            Notification::make()->title('Cover image berhasil dipasang')->success()->send();
                        } else {
                            Notification::make()->title('Tidak ditemukan gambar yang sesuai')->warning()->send();
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Informasi')
                ->schema([
                    Infolists\Components\TextEntry::make('topic.title')->label('Topik'),
                    Infolists\Components\TextEntry::make('pool.name')->label('Pool')->placeholder('—'),
                    Infolists\Components\TextEntry::make('status')->label('Status')->badge(),
                    Infolists\Components\TextEntry::make('triggered_by')->label('Trigger'),
                    Infolists\Components\TextEntry::make('ai_provider')->label('Provider'),
                    Infolists\Components\TextEntry::make('ai_model')->label('Model'),
                    Infolists\Components\TextEntry::make('tokens_used')->label('Tokens'),
                    Infolists\Components\TextEntry::make('generation_time_ms')
                        ->label('Durasi (ms)')
                        ->formatStateUsing(fn ($state) => $state ? number_format($state).' ms' : '—'),
                    Infolists\Components\TextEntry::make('created_at')->label('Waktu')->dateTime(),
                ])->columns(3),

            Infolists\Components\Section::make('Prompt')
                ->schema([
                    Infolists\Components\TextEntry::make('prompt_used')
                        ->label('Prompt')
                        ->markdown()
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            Infolists\Components\Section::make('Error')
                ->schema([
                    Infolists\Components\TextEntry::make('error_message')
                        ->label('Pesan Error')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->visible(fn ($record) => ! empty($record->error_message)),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleGenerationLogs::route('/'),
        ];
    }
}
