<?php

namespace App\Services\Waha;

use App\Models\Member;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

/**
 * Mengirim teks ke banyak anggota aktif (opt-in + nomor) dengan jeda antar pesan.
 */
final class WhatsAppMemberNotifier
{
    public function __construct(
        private readonly WahaClient $client,
    ) {}

    /**
     * @param  LazyCollection<int, Member>|Collection<int, Member>|iterable<Member>  $members
     * @return list<array{member_id: int, ok: bool, error?: string}>
     */
    public function sendToMembers(iterable $members, string $text, ?int $maxRecipients = null): array
    {
        if (! $this->client->isConfigured()) {
            throw WahaException::notConfigured();
        }

        $delayUs = max(0, (int) config('waha.broadcast.delay_ms_between_sends', 1500)) * 1000;
        $max = $maxRecipients ?? (int) config('waha.broadcast.max_recipients_per_job', 100);
        $results = [];
        $n = 0;

        foreach ($members as $member) {
            if (++$n > $max) {
                break;
            }

            $chatId = MemberPhone::toChatId($member->phone);
            if ($chatId === null) {
                $results[] = ['member_id' => (int) $member->id, 'ok' => false, 'error' => 'invalid_phone'];

                continue;
            }

            $sent = false;
            $lastError = null;
            for ($attempt = 0; $attempt < 3; $attempt++) {
                try {
                    $this->client->sendText($chatId, $text);
                    $sent = true;
                    break;
                } catch (WahaException $e) {
                    $lastError = $e->getMessage();
                    if ($attempt < 2) {
                        $backoffMs = (int) config('waha.broadcast.retry_backoff_ms', 1000) * (2 ** $attempt);
                        usleep($backoffMs * 1000);
                    }
                }
            }

            if ($sent) {
                $results[] = ['member_id' => (int) $member->id, 'ok' => true];
            } else {
                Log::warning('WhatsAppMemberNotifier: send failed after retries', [
                    'member_id' => $member->id,
                    'error' => $lastError,
                ]);
                $results[] = ['member_id' => (int) $member->id, 'ok' => false, 'error' => $lastError];
            }

            if ($delayUs > 0) {
                usleep($delayUs);
            }
        }

        return $results;
    }

    /**
     * Anggota aktif, opt-in WA, nomor terisi.
     *
     * @return LazyCollection<int, Member>
     */
    public function eligibleMembersQuery(): LazyCollection
    {
        return Member::query()
            ->where('status', 'active')
            ->where('whatsapp_notifications', true)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderBy('id')
            ->cursor();
    }
}
