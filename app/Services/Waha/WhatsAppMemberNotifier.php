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

            try {
                $this->client->sendText($chatId, $text);
                $results[] = ['member_id' => (int) $member->id, 'ok' => true];
            } catch (WahaException $e) {
                Log::warning('WhatsAppMemberNotifier: send failed', [
                    'member_id' => $member->id,
                    'error' => $e->getMessage(),
                ]);
                $results[] = ['member_id' => (int) $member->id, 'ok' => false, 'error' => $e->getMessage()];
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
