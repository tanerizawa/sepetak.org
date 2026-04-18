@extends('layouts.app')

@section('title', $album->title . ' — Galeri SEPETAK')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($album->description), 160))

@push('styles')
@include('partials.jsonld.breadcrumb', ['items' => [
    ['name' => 'Beranda', 'url' => route('beranda')],
    ['name' => 'Galeri', 'url' => route('gallery.index')],
    ['name' => $album->title, 'url' => url()->current()],
]])
@endpush

@section('content')

{{-- Album header --}}
<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-4 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <a href="{{ route('gallery.index') }}" class="hover:underline">Galeri</a>
            <span class="text-flag-500">//</span>
            <span>{{ $album->title }}</span>
        </nav>
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl leading-[0.9] uppercase">
            {{ $album->title }}
        </h1>
        @if($album->description)
            <p class="mt-4 max-w-3xl text-ink-700 text-lg leading-relaxed">{{ $album->description }}</p>
        @endif
        <div class="mt-4 meta-stamp flex items-center gap-4 flex-wrap">
            @if($album->event_date)
                <span>{{ $album->event_date->translatedFormat('d F Y') }}</span>
            @endif
            @if($album->location)
                <span>{{ $album->location }}</span>
            @endif
            <span>{{ $album->items->count() }} media</span>
        </div>
    </div>
</section>

{{-- Gallery content --}}
<section class="py-16 bg-paper-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @php
            $photos = $album->items->where('type', 'photo');
            $videos = $album->items->where('type', 'video');
        @endphp

        {{-- Photos --}}
        @if($photos->count())
        <div class="mb-16">
            <h2 class="font-display text-3xl uppercase mb-8 flex items-center gap-3">
                <span class="inline-block w-8 h-1 bg-flag-500"></span>
                Foto
                <span class="meta-stamp ml-2">{{ $photos->count() }}</span>
            </h2>
            <div class="gallery-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($photos as $idx => $item)
                    @php($thumbUrl = $item->getFirstMediaUrl('gallery_photo', 'thumb') ?: $item->getFirstMediaUrl('gallery_photo'))
                    @php($fullUrl = $item->getFirstMediaUrl('gallery_photo', 'preview') ?: $item->getFirstMediaUrl('gallery_photo'))
                    @if($thumbUrl)
                    <button
                        type="button"
                        class="gallery-thumb card-poster group block overflow-hidden cursor-pointer aspect-square"
                        data-lightbox-index="{{ $idx }}"
                        data-lightbox-src="{{ $fullUrl }}"
                        data-lightbox-caption="{{ $item->caption ?? $item->title ?? '' }}"
                        data-lightbox-credit="{{ $item->credit ?? '' }}"
                    >
                        <div class="relative w-full h-full">
                            <img
                                src="{{ $thumbUrl }}"
                                alt="{{ $item->title ?? $album->title }}"
                                class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-flag-500/20 mix-blend-multiply group-hover:bg-transparent transition-all duration-500"></div>
                            @if($item->title || $item->caption)
                            <div class="absolute bottom-0 inset-x-0 p-3 bg-gradient-to-t from-ink-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span class="text-paper-50 text-xs font-mono uppercase tracking-wider line-clamp-2">{{ $item->title ?? $item->caption }}</span>
                            </div>
                            @endif
                        </div>
                    </button>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Videos --}}
        @if($videos->count())
        <div>
            <h2 class="font-display text-3xl uppercase mb-8 flex items-center gap-3">
                <span class="inline-block w-8 h-1 bg-flag-500"></span>
                Video
                <span class="meta-stamp ml-2">{{ $videos->count() }}</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($videos as $item)
                    <div class="card-poster overflow-hidden">
                        @if($item->video_embed_url)
                        <div class="video-embed aspect-video border-b-4 border-ink-900">
                            <iframe
                                src="{{ $item->video_embed_url }}"
                                title="{{ $item->title ?? 'Video' }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                class="w-full h-full"
                                loading="lazy"
                            ></iframe>
                        </div>
                        @elseif($item->video_thumbnail)
                        <a href="{{ $item->video_url }}" target="_blank" rel="noopener" class="block relative aspect-video group">
                            <img src="{{ $item->video_thumbnail }}" alt="{{ $item->title ?? 'Video thumbnail' }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-16 h-16 bg-flag-500 border-4 border-ink-900 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-paper-50 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </a>
                        @endif
                        <div class="p-5 bg-paper-50">
                            @if($item->title)
                                <h3 class="font-display text-xl uppercase">{{ $item->title }}</h3>
                            @endif
                            @if($item->caption)
                                <p class="text-ink-700 text-sm mt-1">{{ $item->caption }}</p>
                            @endif
                            @if($item->credit)
                                <p class="meta-stamp mt-2">{{ $item->credit }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Empty state --}}
        @if(!$photos->count() && !$videos->count())
        <div class="border-4 border-dashed border-ink-900 bg-paper-100 p-16 text-center">
            <x-rev.icon name="sun" size="64" class="mx-auto text-ink-900 mb-4"/>
            <h3 class="font-display text-3xl uppercase text-ink-900 mb-2">Album Masih Kosong</h3>
            <p class="text-ink-700 font-mono uppercase tracking-widest text-sm">Foto dan video belum ditambahkan ke album ini.</p>
        </div>
        @endif

        {{-- Back link --}}
        <div class="mt-16 pt-8 border-t-4 border-ink-900">
            <a href="{{ route('gallery.index') }}" class="btn-rev btn-rev-ghost inline-flex items-center gap-2">
                <x-rev.icon name="arrow-left" size="16"/>
                Kembali ke Galeri
            </a>
        </div>
    </div>
</section>

{{-- Lightbox --}}
@if($photos->count())
<div
    id="gallery-lightbox"
    class="fixed inset-0 z-[100] hidden bg-ink-900/95 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-label="Tampilan foto"
>
    <button type="button" id="lightbox-close" class="absolute top-4 right-4 z-10 w-12 h-12 bg-flag-500 border-4 border-ink-900 text-paper-50 font-display text-xl hover:bg-flag-600 transition-colors" aria-label="Tutup">
        &times;
    </button>

    <button type="button" id="lightbox-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-paper-50 border-4 border-ink-900 text-ink-900 font-display text-xl hover:bg-flag-500 hover:text-paper-50 transition-colors" aria-label="Sebelumnya">
        &#8592;
    </button>

    <button type="button" id="lightbox-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-paper-50 border-4 border-ink-900 text-ink-900 font-display text-xl hover:bg-flag-500 hover:text-paper-50 transition-colors" aria-label="Berikutnya">
        &#8594;
    </button>

    <div class="flex items-center justify-center h-full p-16">
        <div class="max-w-5xl w-full">
            <img id="lightbox-img" src="" alt="" class="mx-auto max-h-[75vh] object-contain border-4 border-paper-50/20">
            <div class="mt-4 text-center">
                <p id="lightbox-caption" class="text-paper-50 font-mono text-sm uppercase tracking-wider"></p>
                <p id="lightbox-credit" class="text-paper-200 font-mono text-xs uppercase tracking-wider mt-1"></p>
                <p id="lightbox-counter" class="text-flag-500 font-mono text-xs uppercase tracking-widest mt-2"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('gallery-lightbox');
    const img = document.getElementById('lightbox-img');
    const caption = document.getElementById('lightbox-caption');
    const credit = document.getElementById('lightbox-credit');
    const counter = document.getElementById('lightbox-counter');
    const thumbs = Array.from(document.querySelectorAll('.gallery-thumb'));
    const closeBtn = document.getElementById('lightbox-close');
    const prevBtn = document.getElementById('lightbox-prev');
    const nextBtn = document.getElementById('lightbox-next');
    let current = 0;
    let lastTrigger = null;

    const focusableSelector = 'button, a[href], [tabindex]:not([tabindex="-1"])';

    function trapFocus(e) {
        if (lightbox.classList.contains('hidden')) return;
        if (e.key !== 'Tab') return;
        const focusable = lightbox.querySelectorAll(focusableSelector);
        if (!focusable.length) return;
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    }

    function show(index) {
        const thumb = thumbs[index];
        if (!thumb) return;
        current = index;
        img.src = thumb.dataset.lightboxSrc;
        img.alt = thumb.dataset.lightboxCaption || '';
        caption.textContent = thumb.dataset.lightboxCaption || '';
        credit.textContent = thumb.dataset.lightboxCredit ? 'Foto: ' + thumb.dataset.lightboxCredit : '';
        counter.textContent = (index + 1) + ' / ' + thumbs.length;
        lightbox.classList.remove('hidden');
        lightbox.setAttribute('role', 'dialog');
        lightbox.setAttribute('aria-modal', 'true');
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', trapFocus);
        setTimeout(function () { closeBtn.focus(); }, 50);
    }

    function hide() {
        lightbox.classList.add('hidden');
        document.body.style.overflow = '';
        document.removeEventListener('keydown', trapFocus);
        if (lastTrigger && document.body.contains(lastTrigger)) {
            lastTrigger.focus();
        }
    }

    function prev() { show((current - 1 + thumbs.length) % thumbs.length); }
    function next() { show((current + 1) % thumbs.length); }

    thumbs.forEach(function(thumb, i) {
        thumb.addEventListener('click', function() {
            lastTrigger = thumb;
            show(i);
        });
    });

    closeBtn.addEventListener('click', hide);
    prevBtn.addEventListener('click', prev);
    nextBtn.addEventListener('click', next);

    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) hide();
    });

    document.addEventListener('keydown', function(e) {
        if (lightbox.classList.contains('hidden')) return;
        if (e.key === 'Escape') hide();
        if (e.key === 'ArrowLeft') prev();
        if (e.key === 'ArrowRight') next();
    });
});
</script>
@endpush
@endif

@endsection
