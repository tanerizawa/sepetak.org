@extends('layouts.app')

@section('title', ($archiveTitle ?? 'Arsip') . ' — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', $archiveMeta ?? ('Arsip publikasi ' . $archiveTitle . ' di ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK') . '.'))

@section('content')

<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-4 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <a href="{{ route('posts.index') }}" class="hover:underline">Artikel</a>
            <span class="text-flag-500">//</span>
            <span>{{ $archiveTitle }}</span>
        </nav>
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl leading-[0.9] uppercase">
            {{ $archiveTitle }}
        </h1>
        @if($archiveMeta)
            <p class="mt-4 max-w-2xl text-ink-700 text-lg leading-relaxed">{{ $archiveMeta }}</p>
        @endif
    </div>
</section>

<section class="py-16 bg-paper-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($posts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    @php
                        $cover = $post->getFirstMediaUrl('cover');
                        $dateLabel = $post->published_at ? $post->published_at->translatedFormat('d M Y') : '—';
                    @endphp
                    <x-rev.card
                        :href="route('posts.show', $post->slug)"
                        :image="$cover ?: null"
                        :image-alt="$post->title"
                        :meta="$dateLabel"
                        :title="$post->title"
                        :excerpt="$post->excerpt"
                    >
                        <div class="mt-4 flex items-center gap-2 font-display uppercase tracking-widest text-sm text-flag-600">
                            Baca selengkapnya
                            <x-rev.icon name="arrow-right" size="14"/>
                        </div>
                    </x-rev.card>
                @endforeach
            </div>

            <div class="mt-12 font-mono text-sm">
                {{ $posts->links() }}
            </div>
        @else
            <div class="border-4 border-dashed border-ink-900 bg-paper-100 p-16 text-center">
                <x-rev.icon name="megaphone" size="64" class="mx-auto text-ink-900 mb-4"/>
                <h3 class="font-display text-3xl uppercase text-ink-900 mb-2">Belum Ada Artikel</h3>
                <p class="text-ink-700 font-mono uppercase tracking-widest text-sm">Belum ada publikasi pada arsip ini.</p>
            </div>
        @endif
    </div>
</section>

@endsection
