<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\Waha\WhatsAppMemberNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyMembersPostPublishedWhatsAppJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 3600;

    public function __construct(
        public int $postId,
    ) {}

    public function uniqueId(): string
    {
        return 'waha-post-published-'.$this->postId;
    }

    public function handle(WhatsAppMemberNotifier $notifier): void
    {
        if (! config('waha.enabled', false) || ! config('waha.auto.on_post_published', false)) {
            return;
        }

        $post = Post::query()->find($this->postId);
        if ($post === null || $post->status !== 'published') {
            return;
        }

        $url = route('posts.show', ['slug' => $post->slug], true);
        $template = (string) config('waha.templates.post_published');
        $text = strtr($template, [
            ':title' => $post->title,
            ':url' => $url,
        ]);

        $results = $notifier->sendToMembers($notifier->eligibleMembersQuery(), $text);
        $ok = count(array_filter($results, fn (array $r) => $r['ok'] ?? false));
        Log::info('WAHA: post published broadcast finished', [
            'post_id' => $this->postId,
            'sent_ok' => $ok,
            'total_attempts' => count($results),
        ]);
    }
}
