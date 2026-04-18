<?php

namespace App\Filament\Pages;

use App\Jobs\ManualWhatsAppBroadcastJob;
use App\Models\Member;
use App\Services\Waha\WahaClient;
use App\Services\Waha\WahaException;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class WhatsAppOperationsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string $view = 'filament.pages.whatsapp-operations';

    /** Grup khusus agar menu mudah ditemukan di sidebar. */
    protected static ?string $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'WhatsApp & WAHA';

    protected static ?string $navigationLabel = 'WhatsApp & WAHA';

    protected static ?string $slug = 'whatsapp-waha';

    public ?array $data = [];

    public ?string $connectionResult = null;

    public int $eligibleMemberCount = 0;

    public function mount(): void
    {
        $this->authorizeAccess();
        $this->eligibleMemberCount = $this->countEligibleMembers();
        $this->form->fill(['broadcast_message' => '']);
    }

    protected function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()?->hasAnyRole(['superadmin', 'admin']),
            403,
            'Anda tidak berhak mengakses halaman ini.'
        );
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['superadmin', 'admin']) ?? false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ringkasan')
                    ->description('Anggota **aktif** dengan nomor telepon terisi dan opsi broadcast WA diaktifkan.')
                    ->schema([
                        Forms\Components\Placeholder::make('eligible')
                            ->label('Siap terima broadcast')
                            ->content(fn () => (string) $this->eligibleMemberCount),
                        Forms\Components\Placeholder::make('env_hint')
                            ->label('Konfigurasi server')
                            ->content(new HtmlString(
                                '<p class="text-sm text-gray-600 dark:text-gray-400">'
                                .'Variabel lingkungan: <code>WAHA_ENABLED</code>, <code>WAHA_BASE_URL</code>, '
                                .'<code>WAHA_API_KEY</code>, <code>WAHA_SESSION</code>, '
                                .'<code>WAHA_AUTO_POST_PUBLISHED</code>, <code>WAHA_AUTO_EVENT_PUBLIC</code>. '
                                .'Kunci API hanya di <code>.env</code>, tidak disimpan di basis data.</p>'
                            )),
                    ]),
                Forms\Components\Section::make('Dokumentasi WAHA')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Placeholder::make('waha_links')
                            ->label('')
                            ->content(new HtmlString(
                                '<ul class="list-disc space-y-1 ps-4 text-sm">'
                                .'<li><a href="https://github.com/devlikeapro/waha" target="_blank" rel="noopener" class="text-primary-600 underline">WAHA di GitHub (devlikeapro/waha)</a></li>'
                                .'<li><a href="https://waha.devlike.pro/docs/overview/quick-start/" target="_blank" rel="noopener" class="text-primary-600 underline">Quick start & sesi / QR</a></li>'
                                .'<li><a href="https://waha.devlike.pro/docs/how-to/send-messages/" target="_blank" rel="noopener" class="text-primary-600 underline">Kirim pesan (chatId, sendText)</a></li>'
                                .'<li><a href="https://waha.devlike.pro/docs/overview/⚠️-how-to-avoid-blocking/" target="_blank" rel="noopener" class="text-primary-600 underline">Hindari pemblokiran (throttle & pola aman)</a></li>'
                                .'</ul>'
                            )),
                    ]),
                Forms\Components\Section::make('Broadcast manual')
                    ->description('Pesan di antrekan ke worker; pengiriman ke banyak nomor membutuhkan waktu (jeda antar pesan).')
                    ->schema([
                        Forms\Components\Textarea::make('broadcast_message')
                            ->label('Isi pesan')
                            ->required()
                            ->rows(6)
                            ->maxLength(4096)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('Periksa koneksi WAHA')
                ->icon('heroicon-o-signal')
                ->action(function (WahaClient $client): void {
                    if (! $client->isConfigured()) {
                        $this->connectionResult = 'WAHA belum diaktifkan atau URL/kunci API kosong (lihat .env).';

                        Notification::make()
                            ->title('Belum dikonfigurasi')
                            ->body($this->connectionResult)
                            ->warning()
                            ->send();

                        return;
                    }

                    try {
                        $session = $client->getSession();
                        $this->connectionResult = json_encode(
                            $session,
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
                        );
                        Notification::make()
                            ->title('Terhubung ke WAHA')
                            ->body('Respons sesi diterima.')
                            ->success()
                            ->send();
                    } catch (WahaException $e) {
                        $this->connectionResult = $e->getMessage();
                        Notification::make()
                            ->title('Gagal menghubungi WAHA')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function queueBroadcast(): void
    {
        $this->authorizeAccess();
        $this->form->validate();

        if (! app(WahaClient::class)->isConfigured()) {
            Notification::make()
                ->title('WAHA belum siap')
                ->body('Aktifkan WAHA_ENABLED dan isi URL serta kunci API.')
                ->warning()
                ->send();

            return;
        }

        $message = trim((string) ($this->form->getState()['broadcast_message'] ?? ''));
        if ($message === '') {
            Notification::make()
                ->title('Pesan kosong')
                ->body('Isi teks broadcast terlebih dahulu.')
                ->warning()
                ->send();

            return;
        }

        ManualWhatsAppBroadcastJob::dispatch($message, (int) auth()->id());

        Notification::make()
            ->title('Broadcast dimasukkan ke antrian')
            ->body('Worker akan mengirim ke anggota yang memenuhi syarat (lihat log server).')
            ->success()
            ->send();
    }

    private function countEligibleMembers(): int
    {
        return Member::query()
            ->where('status', 'active')
            ->where('whatsapp_notifications', true)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->count();
    }
}
