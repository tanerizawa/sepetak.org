@extends('layouts.app')

@section('title', $page->title . ' — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($page->body), 160))

@section('content')

{{-- Page masthead — consistent with posts/show tapi lebih sederhana --}}
<section class="bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-5 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <span>{{ $page->title }}</span>
        </nav>
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl leading-[0.9] uppercase text-ink-900">
            {{ $page->title }}
        </h1>
        <div class="mt-5 flex items-center gap-3">
            <span class="inline-block h-[3px] w-20 bg-flag-500"></span>
            <span class="meta-stamp">Dokumen Resmi</span>
        </div>
    </div>
</section>

{{-- Body --}}
<article class="py-14 bg-paper-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose-rev">
            {!! $page->body !!}
        </div>

        <hr class="my-12 border-t-4 border-ink-900">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-flag-500 pb-1">
                <x-rev.icon name="arrow-right" size="14" class="rotate-180"/>
                Kembali ke Beranda
            </a>
            <x-rev.btn :href="route('member-registration.create')" variant="red">
                <x-rev.icon name="signature" size="18"/>
                Daftar Anggota
            </x-rev.btn>
        </div>
    </div>
</article>

@endsection
