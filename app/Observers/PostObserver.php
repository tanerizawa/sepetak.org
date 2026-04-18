<?php

namespace App\Observers;

use App\Jobs\NotifyMembersPostPublishedWhatsAppJob;
use App\Models\Post;
use App\Services\ArticleImageService;
use Mews\Purifier\Facades\Purifier;

class PostObserver
{
    public function saving(Post $post): void
    {
        if ($post->isDirty('body') && filled($post->body) && $post->source_type !== 'auto_generated') {
            $post->body = Purifier::clean($post->body, 'filament_rich_html');
        }
    }

    public function created(Post $post): void
    {
        if ($post->status === 'published') {
            $this->dispatchWhatsApp($post);
        }
    }

    public function updated(Post $post): void
    {
        $wasJustPublished = $post->wasChanged('status') && $post->status === 'published';

        if ($wasJustPublished && $post->source_type === 'auto_generated' && ! $post->hasMedia('cover')) {
            try {
                app(ArticleImageService::class)->attachCoverImage($post);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($wasJustPublished) {
            $this->dispatchWhatsApp($post);
        }
    }

    private function dispatchWhatsApp(Post $post): void
    {
        if (! class_exists(NotifyMembersPostPublishedWhatsAppJob::class)) {
            return;
        }

        try {
            NotifyMembersPostPublishedWhatsAppJob::dispatch($post->getKey());
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
