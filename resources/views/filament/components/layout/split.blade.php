@php
    $livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    {{-- Halaman auth "Tani Merah" — poster split 40:60 (desktop) / stack (mobile).
         Tetap menggunakan <x-filament-panels::layout.base> agar asset theme,
         CSRF, dan Livewire hydration berjalan seperti halaman Filament biasa. --}}
    <div class="sepetak-login-shell">

        {{-- Poster kiri — identitas organisasi --}}
        <aside class="sepetak-login-poster">
            @php($logoV = config('sepetak.logo_asset_version', '3'))
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 border-2 border-paper-50 bg-paper-50 overflow-hidden">
                    <img
                        src="{{ asset('img/logo/logo-96.png') }}?v={{ $logoV }}"
                        srcset="{{ asset('img/logo/logo-96.png') }}?v={{ $logoV }} 1x, {{ asset('img/logo/logo-128.png') }}?v={{ $logoV }} 2x"
                        alt=""
                        width="48" height="48"
                        class="block w-full h-full object-cover"
                        decoding="async"
                    >
                </div>
                <div class="leading-none">
                    <div class="sepetak-login-meta">Panel Administrasi</div>
                    <div class="font-display text-2xl uppercase tracking-[0.06em] mt-1">SEPETAK</div>
                </div>
            </div>

            <div class="max-w-md">
                <div class="sepetak-login-meta mb-4 flex items-center gap-3">
                    <span class="inline-block h-[3px] w-10 bg-paper-50"></span>
                    <span>Ruang Kerja Internal</span>
                </div>
                <h1 class="sepetak-login-headline">
                    <span class="sepetak-login-headline__line">Serikat</span>
                    <span class="sepetak-login-headline__line">
                        <span class="sepetak-login-headline__mark">Pekerja&nbsp;Tani</span>
                    </span>
                    <span class="sepetak-login-headline__line">Karawang</span>
                </h1>
                <p class="mt-5 text-sm sm:text-base leading-relaxed text-paper-100 max-w-sm">
                    Halaman ini diperuntukkan bagi pengurus dan sekretariat SEPETAK. Masuk menggunakan kredensial yang telah diterbitkan sekretariat untuk mengelola data anggota, kasus agraria, serta publikasi organisasi.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-4 sepetak-login-meta">
                <span>Didirikan 2007</span>
                <span aria-hidden="true" class="opacity-50">·</span>
                <span>Kabupaten Karawang</span>
                <span aria-hidden="true" class="opacity-50">·</span>
                <a href="{{ url('/') }}" class="underline decoration-paper-50/60 hover:decoration-paper-50">Situs Publik</a>
            </div>
        </aside>

        {{-- Form kanan --}}
        <main class="sepetak-login-form fi-simple-main">
            <div class="sepetak-login-form-card">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Footer render hook tetap dijalankan agar konsisten dengan halaman lain. --}}
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire?->getRenderHookScopes()) }}
</x-filament-panels::layout.base>
