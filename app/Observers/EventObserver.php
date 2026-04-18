<?php

namespace App\Observers;

use App\Jobs\NotifyMembersEventPublicWhatsAppJob;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;

class EventObserver
{
    private const DISPATCHED_KEY_PREFIX = 'event_notification_dispatched_';

    public function created(Event $event): void
    {
        $this->queueNotifyIfPublic($event);
    }

    public function updated(Event $event): void
    {
        if (! $event->wasChanged('status')) {
            return;
        }

        if (! in_array($event->status, ['planned', 'done'], true)) {
            return;
        }

        $prev = (string) $event->getOriginal('status');
        if (in_array($prev, ['planned', 'done'], true)) {
            return;
        }

        $this->queueNotifyIfPublic($event);
    }

    private function queueNotifyIfPublic(Event $event): void
    {
        if (! in_array($event->status, ['planned', 'done'], true)) {
            return;
        }

        $cacheKey = self::DISPATCHED_KEY_PREFIX.$event->getKey();
        if (Cache::has($cacheKey)) {
            return;
        }

        try {
            NotifyMembersEventPublicWhatsAppJob::dispatch($event->id);
            Cache::put($cacheKey, true, now()->addHours(24));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
