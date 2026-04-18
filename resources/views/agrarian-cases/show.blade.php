@extends('layouts.app')

@section('title', $case->title . ' — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($case->summary), 160))

@section('content')

@php
    $statusLabel = $statusLabels[$case->status] ?? $case->status;
@endphp

<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-16">
        <nav class="meta-stamp mb-4 flex flex-wrap items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <a href="{{ route('agrarian-cases.index') }}" class="hover:underline">Kasus agraria</a>
            <span class="text-flag-500">//</span>
            <span class="truncate max-w-[12rem] sm:max-w-none">{{ $case->case_code }}</span>
        </nav>
        <div class="font-mono text-[0.65rem] uppercase tracking-widest text-ink-600 mb-2">{{ $case->case_code }}</div>
        <h1 class="font-display text-4xl sm:text-5xl leading-[0.95] uppercase text-ink-900">
            {{ $case->title }}
        </h1>
        <div class="mt-6 flex flex-wrap gap-3">
            <span class="inline-flex items-center px-3 py-1 border-2 border-ink-900 bg-paper-100 font-mono text-xs uppercase tracking-wider">
                Mulai: {{ $case->start_date?->translatedFormat('d F Y') ?? '—' }}
            </span>
            <span class="inline-flex items-center px-3 py-1 border-2 border-flag-500 bg-flag-500 text-paper-50 font-mono text-xs uppercase tracking-wider">
                {{ $statusLabel }}
            </span>
            @if($case->priority)
                <span class="inline-flex items-center px-3 py-1 border-2 border-ochre-500 bg-ochre-500 text-ink-900 font-mono text-xs uppercase tracking-wider">
                    Prioritas: {{ $case->priority }}
                </span>
            @endif
        </div>
        @if($case->location_text)
            <p class="mt-4 text-ink-700">
                <strong class="font-mono uppercase text-sm tracking-wider">Lokasi:</strong>
                {{ $case->location_text }}
            </p>
        @endif
    </div>
</section>

<section class="py-12 bg-paper-50 border-b-4 border-ink-900/10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="border-4 border-ink-900 bg-paper-100 p-6 sm:p-8">
            <h2 class="font-display text-xl uppercase tracking-wider text-ink-900 mb-3">Ringkasan</h2>
            <p class="text-ink-800 leading-relaxed whitespace-pre-line">{{ $case->summary }}</p>
        </div>
        <div class="border-4 border-ink-900 bg-paper-50 p-6 sm:p-8">
            <h2 class="font-display text-xl uppercase tracking-wider text-ink-900 mb-3">Deskripsi</h2>
            <div class="text-ink-800 leading-relaxed prose prose-neutral max-w-none">
                {!! nl2br(e(strip_tags($case->description))) !!}
            </div>
        </div>
        <div class="flex flex-wrap gap-4">
            <x-rev.btn :href="route('agrarian-cases.index')" variant="ghost">
                <x-rev.icon name="arrow-right" size="16" class="rotate-180"/>
                Daftar kasus
            </x-rev.btn>
            <x-rev.btn :href="route('contact.show')" variant="red">
                Hubungi sekretariat
                <x-rev.icon name="arrow-right" size="16"/>
            </x-rev.btn>
        </div>
    </div>
</section>

@endsection
