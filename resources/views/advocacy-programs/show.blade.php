@extends('layouts.app')

@section('title', $program->title . ' — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $program->description))), 160))

@section('content')

@php
    $statusLabel = $statusLabels[$program->status] ?? $program->status;
@endphp

<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-16">
        <nav class="meta-stamp mb-4 flex flex-wrap items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <a href="{{ route('advocacy-programs.index') }}" class="hover:underline">Program advokasi</a>
            <span class="text-flag-500">//</span>
            <span class="truncate max-w-[12rem] sm:max-w-none">{{ $program->program_code }}</span>
        </nav>
        <div class="font-mono text-[0.65rem] uppercase tracking-widest text-ink-600 mb-2">{{ $program->program_code }}</div>
        <h1 class="font-display text-4xl sm:text-5xl leading-[0.95] uppercase text-ink-900">
            {{ $program->title }}
        </h1>
        <div class="mt-6 flex flex-wrap gap-3">
            @if($program->start_date)
                <span class="inline-flex items-center px-3 py-1 border-2 border-ink-900 bg-paper-100 font-mono text-xs uppercase tracking-wider">
                    Mulai: {{ $program->start_date->translatedFormat('d F Y') }}
                </span>
            @endif
            @if($program->end_date)
                <span class="inline-flex items-center px-3 py-1 border-2 border-ink-900/70 bg-paper-100 font-mono text-xs uppercase tracking-wider">
                    Selesai: {{ $program->end_date->translatedFormat('d F Y') }}
                </span>
            @endif
            <span class="inline-flex items-center px-3 py-1 border-2 border-ochre-500 bg-ochre-500 text-ink-900 font-mono text-xs uppercase tracking-wider">
                {{ $statusLabel }}
            </span>
        </div>
        @if($program->location_text)
            <p class="mt-4 text-ink-700">
                <strong class="font-mono uppercase text-sm tracking-wider">Lokasi:</strong>
                {{ $program->location_text }}
            </p>
        @endif
    </div>
</section>

<section class="py-12 bg-paper-50 border-b-4 border-ink-900/10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="border-4 border-ink-900 bg-paper-100 p-6 sm:p-8">
            <h2 class="font-display text-xl uppercase tracking-wider text-ink-900 mb-3">Deskripsi</h2>
            <div class="text-ink-800 leading-relaxed prose prose-neutral max-w-none">
                {!! nl2br(e(strip_tags($program->description))) !!}
            </div>
        </div>

        @if($program->actions->isNotEmpty())
            <div class="border-4 border-ink-900 bg-paper-50 p-6 sm:p-8">
                <h2 class="font-display text-xl uppercase tracking-wider text-ink-900 mb-4">Jejak aksi (publik)</h2>
                <ul class="space-y-4">
                    @foreach($program->actions as $a)
                        @php
                            $typeLabel = $actionTypeLabels[$a->action_type] ?? $a->action_type;
                        @endphp
                        <li class="border-l-4 border-ochre-500 pl-4 py-1">
                            <div class="font-mono text-xs uppercase tracking-wider text-ink-600">
                                {{ $a->action_date?->translatedFormat('d M Y') ?? '—' }}
                                <span class="text-ochre-600">· {{ $typeLabel }}</span>
                            </div>
                            @if($a->notes)
                                <p class="mt-1 text-ink-800 text-sm leading-relaxed">{{ $a->notes }}</p>
                            @endif
                            @if($a->outcome)
                                <p class="mt-1 text-ink-600 text-sm italic">{{ $a->outcome }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-wrap gap-4">
            <x-rev.btn :href="route('advocacy-programs.index')" variant="ghost">
                <x-rev.icon name="arrow-right" size="16" class="rotate-180"/>
                Daftar program
            </x-rev.btn>
            <x-rev.btn :href="route('contact.show')" variant="red">
                Hubungi sekretariat
                <x-rev.icon name="arrow-right" size="16"/>
            </x-rev.btn>
        </div>
    </div>
</section>

@endsection
