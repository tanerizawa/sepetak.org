@extends('layouts.app')

@section('title', 'Program Advokasi — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Program advokasi, pelatihan, dan pengorganisasian Serikat Pekerja Tani Karawang (SEPETAK) — ringkasan status publik.')

@section('content')

<section class="relative bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-4 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <span>Program advokasi</span>
        </nav>
        <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[0.9] uppercase">
            Program <span class="text-ochre-600">Advokasi</span>
        </h1>
        <p class="mt-4 max-w-2xl text-ink-700 text-lg leading-relaxed">
            Ringkasan publik program pendampingan, pendidikan anggota, dan pengorganisasian. Untuk rincian operasional atau kerja sama, hubungi sekretariat melalui halaman <a href="{{ route('contact.show') }}" class="underline decoration-ochre-500 hover:text-ochre-700">Kontak</a>.
        </p>
        @php
            $statusTotal = array_sum($statusCounts);
        @endphp
        @if($statusTotal > 0)
            <div class="mt-10 pt-10 border-t-2 border-ink-900/15" role="region" aria-labelledby="program-status-heading">
                <h2 id="program-status-heading" class="font-display text-lg sm:text-xl uppercase tracking-widest text-ink-900 mb-6">
                    Ringkasan status
                    <span class="font-mono text-sm normal-case tracking-normal text-ink-600">({{ number_format($statusTotal) }} program)</span>
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    @foreach($statusCounts as $statusKey => $count)
                        @php
                            $label = $statusLabels[$statusKey] ?? $statusKey;
                            $isEmpty = $count === 0;
                        @endphp
                        <div class="border-4 border-ink-900 bg-paper-100 px-3 py-4 sm:px-4 sm:py-5 {{ $isEmpty ? 'opacity-60' : '' }}">
                            <div class="font-mono text-2xl sm:text-3xl tabular-nums text-ink-900 leading-none">{{ number_format($count) }}</div>
                            <div class="mt-2 font-mono text-[0.65rem] sm:text-xs uppercase tracking-wider text-ink-700 leading-snug">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<section class="py-16 bg-paper-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($programs->isEmpty())
            <p class="text-ink-700 font-mono text-sm uppercase tracking-wider border-4 border-ink-900 bg-paper-100 p-6">
                Belum ada program yang dipublikasikan di basis data.
            </p>
        @else
            <ul class="space-y-6">
                @foreach($programs as $p)
                    @php
                        $statusLabel = $statusLabels[$p->status] ?? $p->status;
                    @endphp
                    <li class="border-4 border-ink-900 bg-paper-100 p-6 shadow-poster-sm hover:shadow-poster-red transition-shadow">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="font-mono text-[0.65rem] uppercase tracking-widest text-ink-600 mb-1">{{ $p->program_code }}</div>
                                <h2 class="font-display text-2xl sm:text-3xl uppercase text-ink-900 leading-tight">
                                    <a href="{{ route('advocacy-programs.show', $p->program_code) }}" class="hover:text-ochre-700 hover:underline decoration-ochre-500">
                                        {{ $p->title }}
                                    </a>
                                </h2>
                                @php
                                    $plain = strip_tags((string) $p->description);
                                @endphp
                                @if($plain !== '')
                                    <p class="mt-2 text-ink-700 leading-relaxed">{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', $plain)), 220) }}</p>
                                @endif
                                @if($p->location_text)
                                    <p class="mt-2 text-sm font-mono uppercase tracking-wider text-ink-600">
                                        <x-rev.icon name="megaphone" size="14" class="inline-block align-middle mr-1 opacity-70"/>
                                        {{ $p->location_text }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:items-end lg:text-right shrink-0">
                                @if($p->start_date)
                                    <span class="inline-flex items-center justify-center px-3 py-1 border-2 border-ink-900 bg-paper-50 font-mono text-xs uppercase tracking-wider">
                                        Mulai {{ $p->start_date->translatedFormat('d M Y') }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center justify-center px-3 py-1 border-2 border-ochre-500 bg-ochre-500 text-ink-900 font-mono text-xs uppercase tracking-wider">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-12 border-t-2 border-ink-900/10 pt-8">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</section>

@endsection
