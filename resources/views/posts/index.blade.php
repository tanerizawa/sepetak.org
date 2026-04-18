@extends('layouts.app')

@section('title', 'Artikel — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Kumpulan artikel SEPETAK: organisasi, perjuangan agraria, panduan anggota, dan kajian ilmiah atau analitis seputar pekerja tani di Kabupaten Karawang.')

@section('content')

@php
    $hasFilters = filled($q ?? null) || filled($activeCategory ?? null) || filled($activeTag ?? null);
@endphp

{{-- Masthead — selaras archive / detail artikel (poster, bukan gradasi hijau lama) --}}
<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <nav class="meta-stamp mb-5 flex flex-wrap items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <span class="text-ink-900">Artikel</span>
        </nav>
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
            <div class="min-w-0">
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl leading-[0.9] uppercase text-ink-900 tracking-tight">
                    <span class="text-flag-600">Artikel</span> SEPETAK
                </h1>
                <p class="mt-4 max-w-2xl text-ink-700 text-base sm:text-lg leading-relaxed">
                    Publikasi resmi berupa tulisan organisasi, materi advokasi, panduan anggota, serta kajian ilmiah atau analitis; bukan liputan berita harian.
                </p>
            </div>
            <a
                href="{{ url('/feed.xml') }}"
                class="inline-flex items-center gap-2 self-start lg:self-end font-mono text-[0.65rem] uppercase tracking-widest text-ink-900 border-2 border-ink-900 px-3 py-2 hover:bg-flag-500 hover:text-paper-50 hover:border-flag-500 transition-colors"
            >
                <x-rev.icon name="megaphone" size="14" class="shrink-0"/>
                RSS Artikel
            </a>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4">
        <div class="border-t-4 border-flag-500"></div>
    </div>
</section>

{{-- Filter — satu baris: gulir horizontal di layar sangat sempit --}}
<section class="bg-paper-100 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">
        <form
            method="get"
            action="{{ route('posts.index') }}"
            class="flex flex-nowrap items-stretch gap-2 sm:gap-3 overflow-x-auto pb-0.5 [-webkit-overflow-scrolling:touch]"
            role="search"
            aria-label="Saring artikel"
        >
            <label for="filter-q" class="sr-only">Cari judul atau isi</label>
            <input
                id="filter-q"
                type="search"
                name="q"
                value="{{ $q }}"
                placeholder="Cari…"
                title="Cari judul atau isi"
                autocomplete="off"
                class="posts-filter-field min-w-[10rem] flex-1 basis-0 border-4 border-ink-900 bg-paper-50 px-3 text-sm text-ink-900 placeholder:text-ink-500 placeholder:font-mono placeholder:text-[0.65rem] placeholder:uppercase placeholder:tracking-wider focus:outline-none focus-visible:ring-2 focus-visible:ring-flag-500 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-100"
            />

            <label for="filter-category" class="sr-only">Kategori</label>
            <select
                id="filter-category"
                name="category"
                title="Kategori"
                class="posts-filter-field w-36 sm:w-44 shrink-0 border-4 border-ink-900 bg-paper-50 px-2 sm:px-3 text-sm text-ink-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-flag-500 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-100"
            >
                <option value="">Semua kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected($activeCategory === $cat->slug)>{{ $cat->name }}</option>
                @endforeach
            </select>

            <label for="filter-tag" class="sr-only">Tag</label>
            <select
                id="filter-tag"
                name="tag"
                title="Tag"
                class="posts-filter-field w-32 sm:w-36 shrink-0 border-4 border-ink-900 bg-paper-50 px-2 sm:px-3 text-sm text-ink-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-flag-500 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-100"
            >
                <option value="">Semua tag</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->slug }}" @selected($activeTag === $tag->slug)>{{ $tag->name }}</option>
                @endforeach
            </select>

            <button
                type="submit"
                class="posts-filter-field shrink-0 border-4 border-ink-900 bg-flag-500 px-4 font-display text-xs sm:text-sm uppercase tracking-wider text-paper-50 shadow-poster-sm hover:bg-flag-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-ink-900 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-100"
            >
                Cari
            </button>

            @if($hasFilters)
                <a
                    href="{{ route('posts.index') }}"
                    class="posts-filter-field inline-flex shrink-0 items-center justify-center border-4 border-ink-900 bg-paper-50 px-3 font-mono text-[0.65rem] uppercase tracking-wider text-flag-600 hover:bg-paper-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-flag-500 focus-visible:ring-offset-2 focus-visible:ring-offset-paper-100"
                    title="Hapus semua filter"
                >
                    Reset
                </a>
            @endif
        </form>
    </div>
</section>

{{-- Daftar kartu — sama seperti arsip kategori/tag --}}
<section class="py-16 bg-paper-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($posts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    @php
                        $cover = $post->getFirstMediaUrl('cover');
                        $dateLabel = $post->published_at ? $post->published_at->translatedFormat('d F Y') : '—';
                        $excerptPlain = $post->excerpt ? strip_tags($post->excerpt) : '';
                        $excerptShort = $excerptPlain !== '' ? \Illuminate\Support\Str::limit($excerptPlain, 200) : null;
                    @endphp
                    <x-rev.card
                        :href="route('posts.show', $post->slug)"
                        :image="$cover ?: null"
                        :image-alt="$post->title"
                        :meta="$dateLabel"
                        :title="$post->title"
                        :excerpt="$excerptShort"
                    >
                        <div class="mt-4 flex items-center gap-2 font-display uppercase tracking-widest text-sm text-flag-600">
                            Baca selengkapnya
                            <x-rev.icon name="arrow-right" size="14"/>
                        </div>
                    </x-rev.card>
                @endforeach
            </div>

            <div class="mt-12 border-t-2 border-ink-900/10 pt-8 font-mono text-sm text-ink-800 [&_a]:text-flag-600 [&_a:hover]:underline [&_span]:text-ink-600">
                {{ $posts->links() }}
            </div>
        @else
            <div class="border-4 border-dashed border-ink-900 bg-paper-100 p-12 sm:p-16 text-center">
                <x-rev.icon name="megaphone" size="64" class="mx-auto text-ink-900 mb-4"/>
                <h2 class="font-display text-3xl uppercase text-ink-900 mb-2">Belum ada artikel</h2>
                <p class="text-ink-700 max-w-md mx-auto leading-relaxed">
                    @if($hasFilters)
                        Tidak ada artikel yang cocok dengan filter Anda. Ubah kata kunci atau <a href="{{ route('posts.index') }}" class="text-flag-600 underline decoration-2 underline-offset-2 hover:text-flag-700">hapus filter</a>.
                    @else
                        Artikel akan tampil di sini setelah redaksi mempublikasikannya.
                    @endif
                </p>
            </div>
        @endif
    </div>
</section>

@endsection
