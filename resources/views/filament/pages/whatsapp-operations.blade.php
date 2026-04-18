<x-filament-panels::page>
    @if (filled($this->connectionResult))
        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
            <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Hasil pemeriksaan terakhir</p>
            <pre class="max-h-64 overflow-auto whitespace-pre-wrap break-words text-xs text-gray-800 dark:text-gray-200">{{ $this->connectionResult }}</pre>
        </div>
    @endif

    <form wire:submit="queueBroadcast">
        {{ $this->form }}

        <div class="mt-6 flex flex-wrap gap-3">
            <x-filament::button
                type="submit"
                icon="heroicon-o-paper-airplane"
                wire:confirm="Antre broadcast WhatsApp ke semua anggota aktif yang mengizinkan dan punya nomor valid?"
            >
                Kirim ke antrian
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
