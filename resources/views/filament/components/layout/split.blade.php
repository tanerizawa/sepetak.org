@php
    $livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    {{-- Halaman auth "Tani Merah" — poster split 40:60 (desktop) / stack (mobile).
         Warna teks di poster pakai putih/krem eksplisit: util text-paper-* di dark
         memakai token permukaan gelap sehingga kontras di atas merah hancur. --}}
    <div class="sepetak-login-shell">

        {{-- Poster kiri — identitas organisasi --}}
        <aside class="sepetak-login-poster">
            @php($logoV = config('sepetak.logo_asset_version', '3'))
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 border-2 border-white bg-white overflow-hidden">
                    <img
                        src="{{ asset('img/logo/logo-96.png') }}?v={{ $logoV }}"
                        srcset="{{ asset('img/logo/logo-96.png') }}?v={{ $logoV }} 1x, {{ asset('img/logo/logo-128.png') }}?v={{ $logoV }} 2x"
                        alt=""
                        width="48"
                        height="48"
                        class="block w-full h-full object-cover"
                        decoding="async"
                    >
                </div>
                <div class="leading-none">
                    <div class="sepetak-login-meta text-white/90">Panel Administrasi</div>
                    <div class="font-display text-2xl uppercase tracking-[0.06em] mt-1 text-white">SEPETAK</div>
                </div>
            </div>

            <div class="max-w-md">
                <div class="sepetak-login-meta mb-4 flex items-center gap-3 text-white/90">
                    <span class="inline-block h-[3px] w-10 bg-white"></span>
                    <span>Ruang Kerja Internal</span>
                </div>
                <h1 class="sepetak-login-headline">
                    <span class="sepetak-login-headline__line">Serikat</span>
                    <span class="sepetak-login-headline__line">
                        <span class="sepetak-login-headline__mark">Pekerja&nbsp;Tani</span>
                    </span>
                    <span class="sepetak-login-headline__line">Karawang</span>
                </h1>
                <p class="mt-5 text-sm sm:text-base leading-relaxed text-white max-w-sm [text-shadow:0_1px_2px_rgb(0_0_0_/_0.18)]">
                    Halaman ini diperuntukkan bagi pengurus dan sekretariat SEPETAK. Masuk menggunakan kredensial yang telah diterbitkan sekretariat untuk mengelola data anggota, kasus agraria, serta publikasi organisasi.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-4 sepetak-login-meta text-white/90">
                <span>Didirikan 2007</span>
                <span aria-hidden="true" class="opacity-50">·</span>
                <span>Kabupaten Karawang</span>
                <span aria-hidden="true" class="opacity-50">·</span>
                <a href="{{ url('/') }}" class="text-white underline decoration-white/70 underline-offset-2 hover:decoration-white">Situs Publik</a>
            </div>
        </aside>

        {{-- Form kanan --}}
        <main class="sepetak-login-form fi-simple-main">
            <div class="sepetak-login-form-card">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire?->getRenderHookScopes()) }}
</x-filament-panels::layout.base>
