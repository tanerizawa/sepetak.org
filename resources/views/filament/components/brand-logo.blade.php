@php($logoV = config('sepetak.logo_asset_version', '3'))
{{-- Brand logo panel admin — lencana poster 40px + wordmark SEPETAK. --}}
<div class="flex items-center gap-3">
    <div class="w-10 h-10 border-2 border-ink-900 overflow-hidden bg-flag-500 flex-shrink-0 shadow-[3px_3px_0_#0D0D0D]">
        <img
            src="{{ asset('img/logo/logo-64.png') }}?v={{ $logoV }}"
            srcset="{{ asset('img/logo/logo-64.png') }}?v={{ $logoV }} 1x, {{ asset('img/logo/logo-128.png') }}?v={{ $logoV }} 2x"
            alt=""
            width="40"
            height="40"
            class="block w-full h-full object-cover"
            decoding="async"
        >
    </div>
    <div class="leading-none">
        {{-- Di dark mode token paper-* = permukaan gelap; teks selalu pakai ink-* (terang di .dark). --}}
        <div class="font-display text-xl tracking-[0.08em] uppercase text-ink-900">
            SEPETAK
        </div>
        <div class="font-mono text-[0.6rem] uppercase tracking-widest text-ink-700 mt-0.5">
            Panel Administrasi
        </div>
    </div>
</div>
