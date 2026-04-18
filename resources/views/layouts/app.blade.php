<!DOCTYPE html>
@php($theme = request()->query('theme'))
@php($allowedThemes = ['tani-soft', 'kopi-kertas', 'senja-modern'])
@php($defaultTheme = (string) config('sepetak.theme_default', 'tani-soft'))
@php($defaultTheme = in_array($defaultTheme, $allowedThemes, true) ? $defaultTheme : 'tani-soft')
@php($theme = in_array($theme, $allowedThemes, true) ? $theme : $defaultTheme)
@php($themeColors = [
    'tani-soft' => 'hsl(352 62% 45%)',
    'kopi-kertas' => 'hsl(24 45% 34%)',
    'senja-modern' => 'hsl(350 55% 44%)',
])
@php($themeColor = $themeColors[$theme] ?? 'hsl(352 62% 45%)')
<html lang="id" class="scroll-smooth @yield('html_class')" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', \App\Models\SiteSetting::getValue('site_description', 'SEPETAK - Serikat Pekerja Tani Karawang'))">
    <meta name="theme-color" content="{{ $themeColor }}">
    <title>@yield('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))</title>

    {{-- Canonical & Feeds --}}
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" type="application/rss+xml" title="SEPETAK RSS" href="{{ url('/feed.xml') }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ \App\Models\SiteSetting::getValue('site_name', 'SEPETAK') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', $__env->yieldContent('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK')))">
    <meta property="og:description" content="@yield('og_description', $__env->yieldContent('meta_description', \App\Models\SiteSetting::getValue('site_description', 'SEPETAK - Serikat Pekerja Tani Karawang')))">
    <meta property="og:locale" content="id_ID">
    @php($logoV = config('sepetak.logo_asset_version', '3'))
    <meta property="og:image" content="@yield('og_image', asset('img/logo/logo-512.png') . '?v=' . $logoV)">
    <meta property="og:image:alt" content="Logo SEPETAK — Serikat Pekerja Tani Karawang">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo/logo-32.png') }}?v={{ $logoV }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo/logo-16.png') }}?v={{ $logoV }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo/logo-180.png') }}?v={{ $logoV }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', $__env->yieldContent('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK')))">
    <meta name="twitter:description" content="@yield('og_description', $__env->yieldContent('meta_description', \App\Models\SiteSetting::getValue('site_description', 'SEPETAK - Serikat Pekerja Tani Karawang')))">
    <meta name="twitter:image" content="{{ asset('img/logo/logo-512.png') }}?v={{ $logoV }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo+Black&family=Work+Sans:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&family=Roboto+Slab:wght@400;600&display=swap" rel="stylesheet">

    @php($isLocal = app()->environment('local'))
    @php($useViteHot = $isLocal && (bool) config('sepetak.vite_hmr_enabled', false) && file_exists(public_path('hot')))
    @if ($useViteHot)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif (is_readable(public_path('build/manifest.json')))
        @php($manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true) ?: [])
        @php($css = $manifest['resources/css/app.css']['file'] ?? null)
        @php($js = $manifest['resources/js/app.js']['file'] ?? null)

        @if ($css)
            <link rel="stylesheet" href="/build/{{ $css }}">
        @endif
        @if ($js)
            <script type="module" src="/build/{{ $js }}"></script>
        @endif
    @endif
    @stack('styles')

    {{-- JSON-LD: Organization (global, tampil di semua halaman) --}}
    @include('partials.jsonld.organization')
    @stack('head')
</head>
<body class="bg-paper-50 text-ink-900 antialiased @yield('body_class')">

    {{-- Skip link untuk pengguna keyboard/screen-reader --}}
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[100] focus:bg-flag-500 focus:text-paper-50 focus:px-4 focus:py-2 focus:font-display focus:tracking-widest">Lewati ke konten</a>

    {{-- ================================================================
         Strip atas merah — berisi slogan singkat + link cepat
         ================================================================ --}}
    <div class="bg-flag-500 text-paper-50 border-b-4 border-ink-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-9 text-xs">
            <span class="font-mono tracking-widest uppercase hidden sm:inline">SOLIDARITAS · ORGANISASI · PEMBEBASAN</span>
            <span class="font-mono tracking-widest uppercase sm:hidden">TANI MERAH</span>
            <div class="flex items-center gap-4 font-mono tracking-wider uppercase">
                <a href="{{ route('contact.show') }}" class="hover:underline">Kontak</a>
                <span class="opacity-50">|</span>
                <a href="{{ url('/feed.xml') }}" class="hover:underline">RSS</a>
            </div>
        </div>
    </div>

    {{-- ================================================================
         Navigation — sticky, border hitam tebal
         ================================================================ --}}
    <nav class="bg-paper-50/90 border-b-4 border-ink-900 sticky top-0 z-50 shadow-[0_4px_0_hsl(var(--flag-500))] backdrop-blur-[10px] transition-colors duration-200 ease-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ route('beranda') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 border-2 border-ink-900 overflow-hidden bg-flag-500 flex-shrink-0 shadow-[2px_2px_0_hsl(var(--ink-900))] group-hover:opacity-95 transition-opacity">
                        @include('partials.logo-mark')
                    </div>
                    <div class="leading-none">
                        <div class="font-display text-xl text-ink-900 tracking-wider">SEPETAK</div>
                        <div class="font-mono text-[0.6rem] uppercase tracking-widest text-ink-700">Tani Karawang</div>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-4 xl:gap-7">
                    <a href="{{ route('beranda') }}" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Beranda</a>
                    <a href="{{ route('posts.index') }}" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Artikel</a>
                    <a href="{{ route('pages.show', 'tentang-kami') }}" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Tentang</a>
                    <a href="{{ route('pages.show', 'sejarah') }}" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Sejarah</a>
                    <a href="{{ route('pages.show', 'struktur-organisasi') }}" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Struktur</a>
                    <a href="{{ route('pages.show', 'wilayah-kerja') }}" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Wilayah</a>
                    <a href="{{ route('contact.show') }}" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Kontak</a>
                    <x-rev.btn :href="route('member-registration.create')" variant="red" class="!py-2.5 !px-5 text-xs">
                        Daftar Anggota
                        <x-rev.icon name="arrow-right" size="16"/>
                    </x-rev.btn>
                </div>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" aria-label="Buka menu" aria-expanded="false" class="md:hidden p-2 border-2 border-ink-900 bg-paper-50 text-ink-900 hover:bg-ink-900 hover:text-flag-500 transition duration-300 ease-in-out">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile Nav --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t-2 border-ink-900/20 pt-2 space-y-1">
                <a href="{{ route('beranda') }}" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Beranda</a>
                <a href="{{ route('posts.index') }}" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Artikel</a>
                <a href="{{ route('pages.show', 'tentang-kami') }}" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Tentang Kami</a>
                <a href="{{ route('pages.show', 'sejarah') }}" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Sejarah</a>
                <a href="{{ route('pages.show', 'struktur-organisasi') }}" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Struktur</a>
                <a href="{{ route('pages.show', 'wilayah-kerja') }}" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Wilayah Kerja</a>
                <a href="{{ route('contact.show') }}" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Kontak</a>
                <a href="{{ route('member-registration.create') }}" class="mt-3 block btn-rev btn-rev-red w-full justify-center">
                    Daftar Anggota
                </a>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="border-4 border-ink-900 bg-paper-100 text-ink-900 px-4 py-3 flex items-center gap-3 shadow-poster-sm">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-flag-500 text-paper-50">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </span>
            <span class="font-mono tracking-wide uppercase text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="border-4 border-flag-500 bg-paper-50 text-ink-900 px-4 py-3 flex items-center gap-3 shadow-poster-sm">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-flag-500 text-paper-50">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </span>
            <span class="font-mono tracking-wide uppercase text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <main id="main">
        @yield('content')
    </main>

    {{-- ================================================================
         Footer propaganda — blok hitam dengan garis merah tebal
         ================================================================ --}}
    <footer class="bg-ink-900 text-paper-50 mt-20 border-t-8 border-flag-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            {{-- Strip slogan raksasa --}}
            <div class="mb-12 pb-8 border-b-2 border-paper-50/20">
                <div class="font-display text-4xl sm:text-6xl leading-[0.9] text-paper-50 tracking-wide">
                    TANAH UNTUK <span class="text-flag-500">Penggarap</span>.
                </div>
                <p class="mt-3 font-mono tracking-widest uppercase text-xs text-paper-200">
                    Sejak 2007: Serikat Pekerja Tani Karawang
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 border-2 border-paper-50/30 overflow-hidden bg-flag-500 flex-shrink-0">
                            @include('partials.logo-mark')
                        </div>
                        <div>
                            <div class="font-display text-xl tracking-wider">SEPETAK</div>
                            <div class="font-mono text-[0.6rem] uppercase tracking-widest text-paper-200">Serikat Pekerja Tani Karawang</div>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed text-paper-200 max-w-md">
                        {{ \App\Models\SiteSetting::getValue('site_tagline', 'Pekerja Tani Soko Guru Pembebasan: berjuang bersama untuk reforma agraria, keadilan sosial, dan kedaulatan pangan di Karawang.') }}
                    </p>
                </div>

                <div>
                    <h4 class="font-display text-lg tracking-widest text-flag-500 mb-4 uppercase">Navigasi</h4>
                    <ul class="space-y-2 text-sm font-mono uppercase tracking-wider">
                        <li><a href="{{ route('beranda') }}" class="text-paper-100 hover:text-flag-400 hover:underline">Beranda</a></li>
                        <li><a href="{{ route('posts.index') }}" class="text-paper-100 hover:text-flag-400 hover:underline">Artikel</a></li>
                        <li><a href="{{ route('pages.show', 'tentang-kami') }}" class="text-paper-100 hover:text-flag-400 hover:underline">Tentang</a></li>
                        <li><a href="{{ route('pages.show', 'visi-misi') }}" class="text-paper-100 hover:text-flag-400 hover:underline">Visi dan Misi</a></li>
                        <li><a href="{{ route('pages.show', 'sejarah') }}" class="text-paper-100 hover:text-flag-400 hover:underline">Sejarah</a></li>
                        <li><a href="{{ route('pages.show', 'struktur-organisasi') }}" class="text-paper-100 hover:text-flag-400 hover:underline">Struktur</a></li>
                        <li><a href="{{ route('pages.show', 'wilayah-kerja') }}" class="text-paper-100 hover:text-flag-400 hover:underline">Wilayah Kerja</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-display text-lg tracking-widest text-flag-500 mb-4 uppercase">Sekretariat</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <x-rev.icon name="signature" size="18" class="mt-0.5 text-flag-500 flex-shrink-0"/>
                            <span>{{ \App\Models\SiteSetting::getValue('contact_email', 'info@sepetak.org') }}</span>
                        </li>
                        @php($contactPhone = \App\Models\SiteSetting::getValue('contact_phone'))
                        @if(!empty($contactPhone) && $contactPhone !== '+62 xxx xxxx xxxx')
                        <li class="flex items-start gap-3">
                            <x-rev.icon name="megaphone" size="18" class="mt-0.5 text-flag-500 flex-shrink-0"/>
                            <span>{{ $contactPhone }}</span>
                        </li>
                        @endif
                        <li class="flex items-start gap-3">
                            <x-rev.icon name="home" size="18" class="mt-0.5 text-flag-500 flex-shrink-0"/>
                            <span>{{ \App\Models\SiteSetting::getValue('contact_address', 'Karawang, Jawa Barat') }}</span>
                        </li>
                        <li class="mt-4">
                            <a href="{{ route('member-registration.create') }}" class="btn-rev btn-rev-red !py-2 !px-4 text-xs">
                                Bergabung
                                <x-rev.icon name="arrow-right" size="14"/>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t-2 border-paper-50/20 mt-12 pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs font-mono uppercase tracking-widest text-paper-200">
                <p>&copy; {{ date('Y') }} {{ \App\Models\SiteSetting::getValue('site_name', 'SEPETAK') }}. Solidaritas tanpa syarat.</p>
                <p><a href="{{ url('/feed.xml') }}" class="hover:text-flag-400">RSS Feed</a> · <a href="{{ url('/sitemap.xml') }}" class="hover:text-flag-400">Sitemap</a></p>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            var btn = document.getElementById('mobile-menu-btn');
            var menu = document.getElementById('mobile-menu');
            if (!btn || !menu) return;
            btn.addEventListener('click', function () {
                var hidden = menu.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', hidden ? 'false' : 'true');
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
