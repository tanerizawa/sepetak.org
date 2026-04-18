<?php

namespace App\Jobs;

use App\Models\Event;
use App\Services\Waha\WhatsAppMemberNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyMembersEventPublicWhatsAppJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 600;

    public function __construct(
        public int $eventId,
    ) {}

    public function uniqueId(): string
    {
        return 'waha-event-public-'.$this->eventId;
    }

    public function handle(WhatsAppMemberNotifier $notifier): void
    {
        if (! config('waha.enabled', false) || ! config('waha.auto.on_event_public')) {
            return;
        }

        $event = Event::query()->find($this->eventId);
        if ($event === null || ! in_array($event->status, ['planned', 'done'], true)) {
            return;
        }

        $url = url('/agenda');
        $template = (string) config('waha.templates.event_public');
        $date = $event->event_date?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '';
        $location = (string) ($event->location_text ?? '');
        $text = strtr($template, [
            ':title' => $event->title,
            ':url' => $url,
            ':date' => $date,
            ':location' => $location,
        ]);

        $results = $notifier->sendToMembers($notifier->eligibleMembersQuery(), $text);
        $ok = count(array_filter($results, fn (array $r) => $r['ok'] ?? false));
        Log::info('WAHA: event public broadcast finished', [
            'event_id' => $this->eventId,
            'sent_ok' => $ok,
            'total_attempts' => count($results),
        ]);
    }
}
