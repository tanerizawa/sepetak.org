@extends('layouts.app')

@section('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK') . ' | Pekerja Tani Soko Guru Pembebasan')

@push('head')
    @include('partials.jsonld.website')
@endpush

@section('content')

<section class="relative bg-paper-50 overflow-hidden">
    <div class="h-full min-h-[100vh] grid grid-cols-1 lg:grid-cols-2 items-center">
        <div class="relative z-10 max-w-[1200px] mx-auto w-full px-[5%] py-[var(--space-6)] lg:py-0">
            <div class="flex items-center gap-3 mb-6">
                <span class="inline-block h-[3px] w-10 bg-flag-500"></span>
                <span class="meta-stamp text-ink-700">Serikat Pekerja Tani Karawang · Berdiri sejak 10 Desember 2007</span>
            </div>

            <h1 class="font-display text-hero text-ink-900 leading-[0.95] tracking-[-0.01em]">
                Pekerja Tani<br>
                <span class="text-flag-600">Soko Guru</span><br>
                Pembebasan
            </h1>

            <p class="mt-6 max-w-2xl text-ink-700">
                {{ \App\Models\SiteSetting::getValue('hero_intro', 'SEPETAK memperjuangkan reforma agraria sejati, meliputi akses tanah, air, dan benih bagi pekerja tani serta nelayan di wilayah pedesaan dan pesisir Kabupaten Karawang.') }}
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <x-rev.btn :href="route('member-registration.create')" variant="red">
                    <x-rev.icon name="signature" size="18"/>
                    Pendaftaran anggota
                </x-rev.btn>
                <x-rev.btn :href="route('posts.index')" variant="ghost">
                    <x-rev.icon name="megaphone" size="18"/>
                    Arsip artikel
                </x-rev.btn>
            </div>
        </div>

        <div class="relative h-[60vh] lg:h-full bg-paper-100 overflow-hidden">
            <x-rev.landscape-hero class="absolute inset-0 w-full h-full"/>
            <div class="absolute top-5 right-5 z-10 bg-paper-50/85 text-ink-900 px-3 py-1 rounded-sm backdrop-blur-[10px]">
                <span class="font-mono text-[0.65rem] uppercase tracking-widest">Edisi №1 · 2026</span>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     STATS — angka raksasa di atas paper
     ================================================================ --}}
<section class="bg-paper-50 border-y border-ink-900/10">
    <div class="max-w-[1200px] mx-auto px-[5%] py-[var(--space-6)]">
        <x-rev.ticker
            :items="[
                'Tanah Untuk Penggarap',
                'Reforma Agraria Sejati',
                'Benih Adalah Hak',
                'Air Adalah Hak',
                'Solidaritas Buruh Tani',
                'Kedaulatan Pangan',
                'Karawang Bangkit',
            ]"
            tone="red"
        />

        <x-rev.section-title
            eyebrow="Data organisasi"
            title="Indikator kelembagaan">
            Ringkasan indikator operasional per {{ now()->translatedFormat('F Y') }}, meliputi jumlah anggota terverifikasi, kasus agraria yang sedang didampingi, serta program advokasi yang berstatus aktif.
        </x-rev.section-title>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-16">
            <div class="border-t-4 border-ink-900 pt-6 flex flex-col">
                <x-rev.stat :value="number_format($stats['member_count'])" label="Anggota Aktif"/>
                <p class="mt-3 text-sm text-ink-700 leading-relaxed">Anggota pekerja tani dan nelayan yang tercatat dalam administrasi keanggotaan organisasi.</p>
                <a
                    href="{{ route('member-registration.create') }}"
                    class="homepage-stat-more-link mt-5 inline-flex items-center gap-2 self-start font-mono text-xs uppercase tracking-wider text-ink-900 border-b-2 border-ink-900 hover:text-flag-600 hover:border-flag-500 pb-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-ink-900 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-50 rounded-sm"
                >
                    Informasi pendaftaran anggota
                    <x-rev.icon name="arrow-right" size="14" class="shrink-0"/>
                </a>
            </div>
            <div class="border-t-4 border-flag-500 pt-6 flex flex-col">
                <x-rev.stat :value="number_format($stats['case_count'])" label="Kasus Agraria"/>
                <p class="mt-3 text-sm text-ink-700 leading-relaxed">Sengketa agraria yang sedang dalam pendampingan hukum organisasi, mulai dari mediasi hingga proses di pengadilan.</p>
                <a
                    href="{{ route('agrarian-cases.index') }}"
                    class="homepage-stat-more-link mt-5 inline-flex items-center gap-2 self-start font-mono text-xs uppercase tracking-wider text-flag-600 border-b-2 border-flag-500 hover:text-flag-700 hover:border-flag-600 pb-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-flag-600 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-50 rounded-sm"
                >
                    Daftar kasus agraria
                    <x-rev.icon name="arrow-right" size="14" class="shrink-0"/>
                </a>
            </div>
            <div class="border-t-4 border-ochre-500 pt-6 flex flex-col">
                <x-rev.stat :value="number_format($stats['program_count'])" label="Program Advokasi"/>
                <p class="mt-3 text-sm text-ink-700 leading-relaxed">Kegiatan kampanye, pelatihan, dan pengorganisasian yang berstatus berjalan.</p>
                <a
                    href="{{ route('advocacy-programs.index') }}"
                    class="homepage-stat-more-link mt-5 inline-flex items-center gap-2 self-start font-mono text-xs uppercase tracking-wider text-ochre-700 border-b-2 border-ochre-500 hover:text-ochre-600 hover:border-ochre-600 pb-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-ochre-600 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-50 rounded-sm"
                >
                    Daftar program advokasi
                    <x-rev.icon name="arrow-right" size="14" class="shrink-0"/>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     BERITA TERKINI — kartu poster dengan offset shadow
     ================================================================ --}}
@if(($articlePage?->count() ?? 0) > 0)
<section class="bg-paper-50 border-b border-ink-900/10" id="home-articles"
    data-endpoint="{{ route('home.articles') }}"
    data-next-page-url="{{ $articlePage->hasMorePages() ? route('home.articles', array_filter(['category' => $activeHomeCategory ?: null, 'page' => $articlePage->currentPage() + 1])) : '' }}"
>
    <div class="max-w-[1200px] mx-auto px-[5%] py-[var(--space-6)]">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between mb-10">
            <x-rev.section-title
                eyebrow="Naskah publik"
                title="Publikasi artikel"
                class="!mb-0">
                Jelajahi artikel terbaru berdasarkan kategori. Gunakan tab untuk memfilter, lalu scroll untuk memuat artikel berikutnya.
            </x-rev.section-title>
            <x-rev.btn :href="route('posts.index')" variant="ghost" class="self-start md:self-end flex-shrink-0">
                Semua Artikel
                <x-rev.icon name="arrow-right" size="16"/>
            </x-rev.btn>
        </div>

        <div class="flex flex-wrap items-center gap-2 mb-8" role="tablist" aria-label="Filter kategori artikel">
            <a
                href="{{ route('beranda', array_filter(['theme' => request()->query('theme'), 'category' => null])) }}#home-articles"
                class="home-article-tab inline-flex items-center rounded-sm border border-ink-900/15 bg-paper-50 px-3 py-2 text-sm font-semibold text-ink-900 transition duration-300 ease-in-out"
                data-category="" role="tab" aria-selected="{{ empty($activeHomeCategory) ? 'true' : 'false' }}"
            >
                Semua
            </a>
            @foreach($articleCategories as $cat)
                <a
                    href="{{ route('beranda', array_filter(['theme' => request()->query('theme'), 'category' => $cat->slug])) }}#home-articles"
                    class="home-article-tab inline-flex items-center rounded-sm border border-ink-900/15 bg-paper-50 px-3 py-2 text-sm font-semibold text-ink-900 transition duration-300 ease-in-out"
                    data-category="{{ $cat->slug }}" role="tab" aria-selected="{{ ($activeHomeCategory ?? '') === $cat->slug ? 'true' : 'false' }}"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <div class="home-articles-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @include('partials.home-article-cards', ['posts' => $articlePage->getCollection()])
        </div>

        <div class="mt-10 flex items-center justify-center">
            <button
                type="button"
                class="home-articles-more hidden btn-rev btn-rev-ghost"
                aria-label="Muat artikel lainnya"
            >
                Muat lagi
                <x-rev.icon name="arrow-right" size="16"/>
            </button>
        </div>

        <div class="home-articles-sentinel h-10"></div>

        <div class="home-articles-pagination mt-8 border-t-2 border-ink-900/10 pt-6">
            {{ $articlePage->onEachSide(1)->links() }}
        </div>

        <template id="home-article-skeleton">
            <div class="overflow-hidden rounded-sm border border-ink-900/10 bg-paper-50 shadow-[0_10px_30px_-24px_rgb(13_13_13_/_0.35)]">
                <div class="aspect-video bg-paper-100 animate-pulse"></div>
                <div class="p-5 space-y-3">
                    <div class="h-3 w-1/2 bg-ink-100 animate-pulse"></div>
                    <div class="h-5 w-11/12 bg-ink-100 animate-pulse"></div>
                    <div class="h-5 w-9/12 bg-ink-100 animate-pulse"></div>
                    <div class="h-4 w-full bg-ink-100 animate-pulse"></div>
                    <div class="h-4 w-5/6 bg-ink-100 animate-pulse"></div>
                </div>
            </div>
        </template>
    </div>
</section>
@endif

{{-- ================================================================
     TENTANG — blok hitam dengan grid program (4 kartu)
     ================================================================ --}}
<section class="bg-ink-900 text-paper-50 border-y-4 border-flag-500">
    <div class="max-w-[1200px] mx-auto px-[5%] py-[var(--space-6)]">
        <div class="grid grid-cols-1 lg:grid-cols-[5fr_6fr] gap-14 items-start">
            <div>
                <div class="meta-stamp text-flag-500 mb-4 flex items-center gap-3">
                    <span class="inline-block h-[3px] w-8 bg-flag-500"></span>
                    Profil singkat
                </div>
                <h2 class="font-display text-hero leading-[0.98] text-paper-50">
                    Rebut kedaulatan agraria, <span class="text-flag-300">bangun industrialisasi pertanian</span>.
                </h2>
                <div class="mt-6 space-y-5 text-paper-100 text-base sm:text-lg leading-[1.75]">
                    <p>
                        <strong class="text-paper-50">SEPETAK</strong> (<strong class="text-paper-50">Serikat Pekerja Tani Karawang</strong>) — organisasi massa pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat. Berdiri dari Kongres I (2007); nama resmi disegarkan pada Kongres IV (2020).
                    </p>
                    <p class="text-paper-200">
                        Memperjuangkan reforma agraria, kedaulatan pangan, pesisir, dan keadilan sosial buruh tani, dari pedalaman hingga utara Kabupaten Karawang.
                    </p>
                </div>
                <div class="mt-8 flex flex-wrap gap-4">
                    <x-rev.btn :href="route('pages.show', 'tentang-kami')" variant="red">
                        Profil lengkap
                        <x-rev.icon name="arrow-right" size="16"/>
                    </x-rev.btn>
                    <a href="{{ route('pages.show', 'visi-misi') }}" class="font-sans font-semibold text-sm text-paper-50 border-b border-flag-300 hover:text-flag-200 pb-1 self-center transition duration-300 ease-in-out">
                        Visi dan Misi
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach([
                    ['icon' => 'scales',    'title' => 'Advokasi Hukum',    'desc' => 'Pendampingan hukum dalam sengketa agraria, meliputi mediasi hingga proses di Pengadilan Tata Usaha Negara.'],
                    ['icon' => 'wheat',     'title' => 'Pemberdayaan Tani', 'desc' => 'Pelatihan pertanian agroekologi serta pendampingan koperasi unit desa.'],
                    ['icon' => 'fist',      'title' => 'Pengorganisasian', 'desc' => 'Penguatan basis anggota pada tingkat desa di wilayah Kabupaten Karawang.'],
                    ['icon' => 'megaphone', 'title' => 'Kampanye Publik',  'desc' => 'Advokasi kebijakan publik yang berpihak pada pekerja tani serta pembelaan dalam kasus kriminalisasi petani.'],
                ] as $i => $item)
                    <div class="relative border border-paper-50/25 p-6 bg-ink-800 hover:bg-ink-700 transition duration-300 ease-in-out group">
                        <div class="absolute -top-2 -left-2 bg-flag-500 text-paper-50 px-2 py-0.5 font-mono text-[0.6rem] uppercase tracking-widest">
                            №{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <x-rev.icon :name="$item['icon']" size="36" class="text-flag-500 mb-3"/>
                        <h4 class="font-sans text-xl font-semibold text-paper-50 mb-2 leading-snug">{{ $item['title'] }}</h4>
                        <p class="text-sm leading-relaxed text-paper-200">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     CTA FINAL — blok merah dengan headline besar
     ================================================================ --}}
<section class="relative bg-flag-500 text-paper-50 overflow-hidden">
    {{-- Ornamen diagonal sinar matahari --}}
    <div class="absolute inset-0 pointer-events-none opacity-20">
        <svg viewBox="0 0 800 400" class="w-full h-full" preserveAspectRatio="xMidYMid slice">
            <g stroke="hsl(var(--ink-900))" stroke-width="3">
                @for ($i = 0; $i < 18; $i++)
                    <line x1="{{ $i * 50 }}" y1="0" x2="{{ $i * 50 - 300 }}" y2="400"/>
                @endfor
            </g>
        </svg>
    </div>

    <div class="relative max-w-[1200px] mx-auto px-[5%] py-[var(--space-6)] text-center">
        <div class="meta-stamp text-paper-50 mb-5 flex items-center justify-center gap-3">
            <span class="inline-block h-[3px] w-10 bg-paper-50"></span>
            <span>Keanggotaan</span>
            <span class="inline-block h-[3px] w-10 bg-paper-50"></span>
        </div>
        <h2 class="font-display text-hero leading-[0.95] tracking-[-0.005em]">
            SEPETAK<br>
            <span class="inline-block bg-ink-900 text-flag-200 px-4 py-2 rounded-sm">Serikat Pekerja Tani Karawang</span>
        </h2>
        <p class="mt-8 text-lg sm:text-xl text-paper-100 max-w-2xl mx-auto leading-[1.75] text-pretty">
            Organisasi membuka kesempatan bagi pekerja tani, nelayan kecil, serta pihak yang menyatakan solidaritas untuk menjadi anggota: lengkapi formulir keanggotaan, berkoordinasi dengan pengurus di tingkat basis, serta memperkuat perjuangan agraria dari wilayah kerja masing-masing.
        </p>
        <div class="mt-10 flex flex-wrap justify-center gap-5">
            <a href="{{ route('member-registration.create') }}" class="btn-rev bg-ink-900 text-paper-50 border-paper-50 shadow-[4px_4px_0_hsl(var(--paper-50))] hover:shadow-[6px_6px_0_hsl(var(--paper-50))] hover:-translate-x-0.5 hover:-translate-y-0.5 transition">
                <x-rev.icon name="signature" size="18"/>
                Formulir keanggotaan
            </a>
            <a href="{{ route('contact.show') }}" class="btn-rev bg-transparent text-paper-50 border-paper-50 hover:bg-paper-50 hover:text-flag-500 transition">
                Hubungi Sekretariat
                <x-rev.icon name="arrow-right" size="18"/>
            </a>
        </div>
    </div>
</section>

@endsection
