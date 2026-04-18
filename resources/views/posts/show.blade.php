@extends('layouts.app')

@section('title', $post->title . ' — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', $post->excerpt ?: Str::limit(strip_tags($post->body), 160))
@section('og_type', 'article')
@section('og_title', $post->title)
@section('og_description', $post->excerpt ?: Str::limit(strip_tags($post->body), 200))

@push('head')
    @include('partials.jsonld.news-article', ['post' => $post])
@endpush

@section('content')

@php
    $cover = $post->getFirstMediaUrl('cover');
@endphp

{{-- Header — poster masthead gaya Pravda/People's Daily --}}
<section class="bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
        <nav class="meta-stamp mb-6 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <a href="{{ route('posts.index') }}" class="hover:underline">Berita</a>
            <span class="text-flag-500">//</span>
            <span class="line-clamp-1 max-w-xs">{{ $post->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-10 items-end">
            <div>
                <div class="meta-stamp flex items-center gap-3 mb-4">
                    @if($post->published_at)
                        <span class="inline-flex items-center gap-1.5">
                            <span class="inline-block h-2 w-2 bg-flag-500"></span>
                            {{ $post->published_at->translatedFormat('d F Y') }}
                        </span>
                    @endif
                    @if($post->author)
                        <span class="opacity-60">·</span>
                        <span>Oleh <strong class="text-ink-900">{{ $post->author->name }}</strong></span>
                    @endif
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl leading-[0.92] uppercase tracking-tight text-ink-900">
                    {{ $post->title }}
                </h1>
                @if($post->excerpt)
                    <p class="mt-6 text-lg sm:text-xl text-ink-700 leading-relaxed max-w-2xl">
                        {{ $post->excerpt }}
                    </p>
                @endif
            </div>

            @if ($cover)
                <div class="relative border-4 border-ink-900 shadow-poster">
                    <img src="{{ $cover }}" alt="{{ $post->title }}" class="block w-full h-auto grayscale"/>
                    <div class="pointer-events-none absolute inset-0 bg-flag-500/25 mix-blend-multiply"></div>
                    <div class="absolute top-2 left-2 bg-ink-900 text-paper-50 px-2 py-0.5 font-mono text-[0.6rem] uppercase tracking-widest">Foto Lapangan</div>
                </div>
            @else
                <div class="hidden lg:flex aspect-[4/3] bg-flag-500 border-4 border-ink-900 shadow-poster items-center justify-center">
                    <x-rev.icon name="megaphone" size="96" class="text-paper-50"/>
                </div>
            @endif
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-5">
        <div class="border-t-4 border-flag-500"></div>
    </div>
</section>

{{-- Body --}}
<article class="py-14 bg-paper-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose-rev">
            {!! $post->body !!}
        </div>

        <hr class="my-12 border-t-4 border-ink-900">

        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-flag-500 pb-1">
                <x-rev.icon name="arrow-right" size="14" class="rotate-180"/>
                Kembali ke Daftar Berita
            </a>
            <x-rev.btn :href="route('member-registration.create')" variant="red">
                <x-rev.icon name="signature" size="18"/>
                Gabung SEPETAK
            </x-rev.btn>
        </div>
    </div>
</article>

@endsection
