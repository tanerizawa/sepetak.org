<?php

namespace App\Jobs;

use App\Services\Waha\WhatsAppMemberNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ManualWhatsAppBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $message,
        public int $requestedByUserId,
    ) {}

    public function handle(WhatsAppMemberNotifier $notifier): void
    {
        if (! config('waha.enabled', false)) {
            return;
        }

        $results = $notifier->sendToMembers($notifier->eligibleMembersQuery(), $this->message);
        $ok = count(array_filter($results, fn (array $r) => $r['ok'] ?? false));

        Log::info('WAHA: manual broadcast finished', [
            'requested_by' => $this->requestedByUserId,
            'sent_ok' => $ok,
            'total_attempts' => count($results),
        ]);
    }
}
