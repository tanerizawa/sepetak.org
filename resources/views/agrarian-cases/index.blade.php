@extends('layouts.app')

@section('title', 'Kasus & Advokasi Agraria — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Daftar kasus agraria dan jejak advokasi Serikat Pekerja Tani Karawang (SEPETAK) di Kabupaten Karawang — ringkasan status publik.')

@section('content')

<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-4 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <span>Kasus agraria</span>
        </nav>
        <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[0.9] uppercase">
            Kasus &amp; <span class="text-flag-600">Advokasi</span>
        </h1>
        <p class="mt-4 max-w-2xl text-ink-700 text-lg leading-relaxed">
            Ringkasan publik pendampingan sengketa tanah, aksi kebijakan, dan arsip organisasi. Untuk rincian sensitif atau pembaruan resmi, hubungi sekretariat melalui halaman <a href="{{ route('contact.show') }}" class="underline decoration-flag-500 hover:text-flag-600">Kontak</a>.
        </p>
    </div>
</section>

<section class="py-16 bg-paper-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($cases->isEmpty())
            <p class="text-ink-700 font-mono text-sm uppercase tracking-wider border-4 border-ink-900 bg-paper-100 p-6">
                Belum ada catatan kasus yang dipublikasikan di basis data.
            </p>
        @else
            <ul class="space-y-6">
                @foreach($cases as $c)
                    @php
                        $statusLabel = $statusLabels[$c->status] ?? $c->status;
                    @endphp
                    <li class="border-4 border-ink-900 bg-paper-100 p-6 shadow-poster-sm hover:shadow-poster-red transition-shadow">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="font-mono text-[0.65rem] uppercase tracking-widest text-ink-600 mb-1">{{ $c->case_code }}</div>
                                <h2 class="font-display text-2xl sm:text-3xl uppercase text-ink-900 leading-tight">
                                    <a href="{{ route('agrarian-cases.show', $c->case_code) }}" class="hover:text-flag-600 hover:underline decoration-flag-500">
                                        {{ $c->title }}
                                    </a>
                                </h2>
                                @if($c->summary)
                                    <p class="mt-2 text-ink-700 leading-relaxed">{{ \Illuminate\Support\Str::limit($c->summary, 220) }}</p>
                                @endif
                                @if($c->location_text)
                                    <p class="mt-2 text-sm font-mono uppercase tracking-wider text-ink-600">
                                        <x-rev.icon name="wheat" size="14" class="inline-block align-middle mr-1 opacity-70"/>
                                        {{ $c->location_text }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:items-end lg:text-right shrink-0">
                                <span class="inline-flex items-center justify-center px-3 py-1 border-2 border-ink-900 bg-paper-50 font-mono text-xs uppercase tracking-wider">
                                    {{ $c->start_date?->translatedFormat('d M Y') ?? '—' }}
                                </span>
                                <span class="inline-flex items-center justify-center px-3 py-1 border-2 border-flag-500 bg-flag-500 text-paper-50 font-mono text-xs uppercase tracking-wider">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-12 border-t-2 border-ink-900/10 pt-8">
                {{ $cases->links() }}
            </div>
        @endif
    </div>
</section>

@endsection
