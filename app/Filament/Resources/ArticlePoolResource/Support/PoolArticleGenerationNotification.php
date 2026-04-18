<?php

namespace App\Filament\Resources\ArticlePoolResource\Support;

use Filament\Notifications\Notification;

final class PoolArticleGenerationNotification
{
    /**
     * @param  array{status: string, title: string, body: string}  $result
     */
    public static function send(array $result): void
    {
        $n = Notification::make()
            ->title($result['title'])
            ->body($result['body']);

        match ($result['status']) {
            'success' => $n->success(),
            'danger' => $n->danger(),
            'warning' => $n->warning(),
            default => $n,
        };

        $n->send();
    }
}
