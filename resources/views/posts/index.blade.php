@extends('layouts.app')

@section('title', 'Berita & Artikel - ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Kumpulan berita, artikel, dan informasi terkini dari SEPETAK seputar perjuangan hak-hak petani Karawang.')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-br from-primary-800 to-primary-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="text-sm text-primary-200 mb-4">
            <a href="{{ route('beranda') }}" class="hover:text-white">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-white">Berita</span>
        </nav>
        <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-3">Berita & Artikel</h1>
        <p class="text-primary-100 text-lg max-w-2xl">
            Informasi, berita, dan perkembangan terbaru seputar perjuangan SEPETAK dan hak-hak petani Karawang.
        </p>
    </div>
</section>

{{-- Content --}}
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($posts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <article class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-gray-100 overflow-hidden group flex flex-col">
                        <div class="bg-gradient-to-br from-primary-100 to-primary-200 h-44 flex items-center justify-center">
                            <svg class="w-16 h-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <time class="text-xs text-gray-400 font-medium">
                                {{ $post->published_at ? $post->published_at->translatedFormat('d F Y') : '' }}
                            </time>
                            <h3 class="text-lg font-semibold text-gray-900 mt-1 mb-2 group-hover:text-primary-700 transition-colors line-clamp-2">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)
                                <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                            @endif
                            <a href="{{ route('posts.show', $post->slug) }}" class="mt-auto inline-flex items-center gap-1 text-primary-600 hover:text-primary-800 text-sm font-medium transition-colors">
                                Baca Selengkapnya
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-200">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">Belum ada berita</h3>
                <p class="text-gray-500">Berita dan artikel akan ditampilkan di sini setelah dipublikasikan.</p>
            </div>
        @endif
    </div>
</section>

@endsection
