@extends('layouts.app')

@section('title', 'Agenda Kegiatan — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Agenda kegiatan, rapat anggota, aksi solidaritas, dan program publik Serikat Pekerja Tani Karawang.')

@push('styles')
@include('partials.jsonld.breadcrumb', ['items' => [
    ['name' => 'Beranda', 'url' => route('beranda')],
    ['name' => 'Agenda', 'url' => url()->current()],
]])
@endpush

@section('content')

{{-- Masthead --}}
<section class="bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-5 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <span>Agenda</span>
        </nav>
        <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[0.9] uppercase text-ink-900">
            Agenda<br>
            <span class="text-flag-600">Kegiatan Organisasi.</span>
        </h1>
        <p class="mt-5 text-ink-700 text-lg leading-relaxed max-w-2xl">
            Jadwal rapat anggota, aksi, diskusi tematik, dan program kerja yang terbuka untuk anggota serta publik.
        </p>
    </div>
</section>

<section class="bg-paper-100 py-16 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="meta-stamp mb-4 flex items-center gap-3">
            <span class="inline-block h-[3px] w-8 bg-flag-500"></span>
            Agenda Mendatang
        </div>

        @if($upcoming->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcoming as $event)
                    <article class="border-4 border-ink-900 bg-paper-50 p-6 shadow-poster">
                        <div class="font-mono text-xs uppercase tracking-widest text-flag-600 mb-2">
                            {{ $event->event_date->translatedFormat('l, d F Y') }}
                            · {{ $event->event_date->translatedFormat('H:i') }} WIB
                        </div>
                        <h2 class="font-display text-2xl uppercase text-ink-900 mb-3 leading-tight">{{ $event->title }}</h2>
                        @if($event->location_text)
                            <p class="meta-stamp text-ink-700 mb-3">
                                <x-rev.icon name="megaphone" size="14" class="inline"/>
                                {{ $event->location_text }}
                            </p>
                        @endif
                        @if($event->description)
                            <p class="text-ink-700 leading-relaxed text-sm">{{ \Illuminate\Support\Str::limit($event->description, 180) }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
            <div class="mt-12 font-mono text-sm">
                {{ $upcoming->links() }}
            </div>
        @else
            <div class="border-4 border-dashed border-ink-900 bg-paper-50 p-16 text-center">
                <x-rev.icon name="megaphone" size="64" class="mx-auto text-ink-900 mb-4"/>
                <h3 class="font-display text-3xl uppercase text-ink-900 mb-2">Belum Ada Agenda</h3>
                <p class="text-ink-700 font-mono uppercase tracking-widest text-sm">Agenda baru akan dipublikasikan sekretariat.</p>
            </div>
        @endif
    </div>
</section>

@if($past->count())
<section class="bg-paper-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="meta-stamp mb-4 flex items-center gap-3">
            <span class="inline-block h-[3px] w-8 bg-flag-500"></span>
            Arsip Kegiatan
        </div>
        <h2 class="font-display text-3xl uppercase text-ink-900 mb-8">Kegiatan Terdahulu</h2>
        <ul class="space-y-3">
            @foreach($past as $event)
                <li class="flex items-start gap-4 border-b-2 border-ink-200 pb-3">
                    <div class="font-mono text-xs uppercase tracking-wider text-ink-500 shrink-0 min-w-[100px]">
                        {{ $event->event_date->translatedFormat('d M Y') }}
                    </div>
                    <div class="font-display uppercase text-ink-900 leading-tight">{{ $event->title }}</div>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

@endsection
