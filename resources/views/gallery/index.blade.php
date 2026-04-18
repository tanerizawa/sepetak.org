@extends('layouts.app')

@section('title', 'Galeri — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Galeri foto dan video kegiatan Serikat Pekerja Tani Karawang (SEPETAK) di Kabupaten Karawang, Jawa Barat.')

@section('content')

{{-- Page header — poster style, split paper/ink --}}
<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20 grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8 items-end">
        <div>
            <nav class="meta-stamp mb-4 flex items-center gap-2">
                <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
                <span class="text-flag-500">//</span>
                <span>Galeri</span>
            </nav>
            <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[0.9] uppercase">
                Galeri <span class="text-flag-600">Dokumentasi</span>
            </h1>
            <p class="mt-4 max-w-2xl text-ink-700 text-lg leading-relaxed">
                Dokumentasi foto dan video kegiatan Serikat Pekerja Tani Karawang di lapangan, forum, dan aksi-aksi perjuangan.
            </p>
        </div>
        <div class="hidden lg:block">
            <div class="border-4 border-ink-900 bg-ink-900 text-paper-50 p-5 shadow-poster-red">
                <div class="font-mono text-[0.65rem] uppercase tracking-widest text-flag-500">Arsip Dokumentasi</div>
                <div class="font-display text-3xl mt-1 leading-none">{{ $albums->total() }} Album</div>
                <div class="font-mono text-xs uppercase tracking-widest text-paper-200 mt-1">Foto &amp; Video</div>
            </div>
        </div>
    </div>
</section>

{{-- Albums grid --}}
<section class="py-16 bg-paper-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($albums->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($albums as $album)
                    @php
                        $cover = $album->getFirstMediaUrl('album_cover');
                        $dateLabel = $album->event_date ? $album->event_date->translatedFormat('d M Y') : ($album->published_at ? $album->published_at->translatedFormat('d M Y') : '—');
                        $itemCount = $album->items_count;
                    @endphp
                    <x-rev.card
                        :href="route('gallery.show', $album->slug)"
                        :image="$cover ?: null"
                        :image-alt="$album->title"
                        :meta="$dateLabel"
                        :title="$album->title"
                        :excerpt="$album->description"
                    >
                        <div class="mt-4 flex items-center justify-between">
                            <span class="meta-stamp">
                                {{ $itemCount }} media
                                @if($album->location) · {{ $album->location }} @endif
                            </span>
                            <span class="font-display uppercase tracking-widest text-sm text-flag-600 flex items-center gap-2">
                                Lihat
                                <x-rev.icon name="arrow-right" size="14"/>
                            </span>
                        </div>
                    </x-rev.card>
                @endforeach
            </div>

            <div class="mt-12 font-mono text-sm">
                {{ $albums->links() }}
            </div>
        @else
            <div class="border-4 border-dashed border-ink-900 bg-paper-100 p-16 text-center">
                <x-rev.icon name="sun" size="64" class="mx-auto text-ink-900 mb-4"/>
                <h3 class="font-display text-3xl uppercase text-ink-900 mb-2">Belum Ada Album</h3>
                <p class="text-ink-700 font-mono uppercase tracking-widest text-sm">Dokumentasi foto dan video akan ditampilkan setelah admin menambahkan album.</p>
            </div>
        @endif

    </div>
</section>

@endsection
