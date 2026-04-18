<?php

namespace App\Observers;

use App\Jobs\NotifyMembersEventPublicWhatsAppJob;
use App\Models\Event;

class EventObserver
{
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

        NotifyMembersEventPublicWhatsAppJob::dispatch($event->id);
    }
}
