@extends('layouts.app')

@section('title', 'Design System | SEPETAK')

@section('content')
<div class="bg-paper-50 text-ink-900">
    <div class="max-w-[1200px] mx-auto px-[5%] py-16">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div>
                <div class="meta-stamp text-ink-700 mb-3">Dokumentasi Visual · Token · Komponen</div>
                <h1 class="font-display text-display leading-[0.92]">Design System</h1>
                <p class="mt-4 max-w-2xl text-ink-700 leading-relaxed">
                    Halaman ini dipakai untuk memvalidasi tipografi (Anton), palet warna yang lebih lembut, spacing berbasis golden ratio, dan komponen utama.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($themes as $key => $label)
                    <a
                        href="{{ route('design-system', ['theme' => $key]) }}"
                        class="inline-flex items-center rounded-sm border border-ink-900/15 bg-paper-50 px-3 py-2 text-sm font-semibold text-ink-900 hover:border-ink-900/30 hover:bg-paper-100 transition duration-300 ease-in-out {{ $theme === $key ? 'ring-2 ring-flag-500 ring-offset-2 ring-offset-paper-50' : '' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-14 grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="card-poster p-7">
                <div class="meta-stamp text-ink-700 mb-4">Typography</div>
                <div class="space-y-5">
                    <div>
                        <div class="meta-stamp text-ink-700 mb-1">H1 / Display</div>
                        <div class="font-display text-display leading-[0.92]">Tanah Untuk Penggarap</div>
                    </div>
                    <div>
                        <div class="meta-stamp text-ink-700 mb-1">H2 / Hero</div>
                        <div class="font-display text-hero leading-[0.95]">Reforma Agraria Sejati</div>
                    </div>
                    <div>
                        <div class="meta-stamp text-ink-700 mb-1">Body</div>
                        <p class="text-base leading-[1.65] text-ink-800">
                            SEPETAK memperjuangkan reforma agraria, kedaulatan pangan, dan keadilan sosial. Sistem jarak dan ukuran memprioritaskan keterbacaan jangka panjang.
                        </p>
                    </div>
                    <div>
                        <div class="meta-stamp text-ink-700 mb-1">Meta stamp</div>
                        <div class="meta-stamp text-ink-900">Kategori · 18 Apr 2026 · 6 Menit Baca</div>
                    </div>
                </div>
            </div>

            <div class="card-poster p-7">
                <div class="meta-stamp text-ink-700 mb-4">Colors</div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="border border-ink-900/15 bg-paper-50 p-4">
                        <div class="h-14 bg-paper-50 border border-ink-900/10"></div>
                        <div class="mt-2 text-sm font-semibold">Paper</div>
                        <div class="meta-stamp text-ink-700">paper-50</div>
                    </div>
                    <div class="border border-ink-900/15 bg-paper-50 p-4">
                        <div class="h-14 bg-ink-900"></div>
                        <div class="mt-2 text-sm font-semibold">Ink</div>
                        <div class="meta-stamp text-ink-700">ink-900</div>
                    </div>
                    <div class="border border-ink-900/15 bg-paper-50 p-4">
                        <div class="h-14 bg-flag-500"></div>
                        <div class="mt-2 text-sm font-semibold">Primary</div>
                        <div class="meta-stamp text-ink-700">flag-500</div>
                    </div>
                    <div class="border border-ink-900/15 bg-paper-50 p-4">
                        <div class="h-14 bg-ochre-500"></div>
                        <div class="mt-2 text-sm font-semibold">Secondary</div>
                        <div class="meta-stamp text-ink-700">ochre-500</div>
                    </div>
                    <div class="border border-ink-900/15 bg-paper-50 p-4">
                        <div class="h-14 bg-earth-500"></div>
                        <div class="mt-2 text-sm font-semibold">Earth</div>
                        <div class="meta-stamp text-ink-700">earth-500</div>
                    </div>
                    <div class="border border-ink-900/15 bg-paper-50 p-4">
                        <div class="h-14 bg-flag-100"></div>
                        <div class="mt-2 text-sm font-semibold">Primary weak</div>
                        <div class="meta-stamp text-ink-700">flag-100</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="card-poster p-7">
                <div class="meta-stamp text-ink-700 mb-4">Components</div>
                <div class="flex flex-wrap gap-3">
                    <x-rev.btn href="#" variant="red">CTA Primary</x-rev.btn>
                    <x-rev.btn href="#" variant="solid">Solid Ink</x-rev.btn>
                    <x-rev.btn href="#" variant="ghost">Ghost</x-rev.btn>
                </div>
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="#" class="card-poster block overflow-hidden">
                        <div class="aspect-video bg-flag-100"></div>
                        <div class="p-5">
                            <div class="meta-stamp text-ink-700">Kategori</div>
                            <div class="mt-2 font-display text-xl leading-snug">Judul Artikel yang Panjangnya Dibatasi</div>
                            <p class="mt-2 text-sm text-ink-700 leading-relaxed">
                                Ringkasan singkat untuk membantu scanning cepat tanpa mengorbankan keterbacaan.
                            </p>
                            <div class="mt-4 meta-stamp text-ink-700">18 Apr 2026 · 6 Menit Baca</div>
                        </div>
                    </a>
                    <div class="border border-ink-900/15 bg-paper-50 p-5">
                        <div class="meta-stamp text-ink-700 mb-2">Meta stamp</div>
                        <div class="meta-stamp text-ink-900">Solidaritas · Organisasi · Pembebasan</div>
                        <div class="mt-6 meta-stamp text-ink-700 mb-2">Callout</div>
                        <div class="border-l-4 border-flag-500 bg-paper-100 p-4 text-sm leading-relaxed text-ink-800">
                            Kontras dan whitespace menjaga ritme membaca. Aksen warna dipakai seperlunya.
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-poster p-7">
                <div class="meta-stamp text-ink-700 mb-4">Spacing (ϕ)</div>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 meta-stamp text-ink-700">S1</div>
                        <div class="h-4 w-[var(--space-1)] bg-ink-900"></div>
                        <div class="meta-stamp text-ink-700">8px</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 meta-stamp text-ink-700">S2</div>
                        <div class="h-4 w-[var(--space-2)] bg-ink-900"></div>
                        <div class="meta-stamp text-ink-700">13px</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 meta-stamp text-ink-700">S3</div>
                        <div class="h-4 w-[var(--space-3)] bg-ink-900"></div>
                        <div class="meta-stamp text-ink-700">21px</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 meta-stamp text-ink-700">S4</div>
                        <div class="h-4 w-[var(--space-4)] bg-ink-900"></div>
                        <div class="meta-stamp text-ink-700">34px</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 meta-stamp text-ink-700">S5</div>
                        <div class="h-4 w-[var(--space-5)] bg-ink-900"></div>
                        <div class="meta-stamp text-ink-700">55px</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 meta-stamp text-ink-700">S6</div>
                        <div class="h-4 w-[var(--space-6)] bg-ink-900"></div>
                        <div class="meta-stamp text-ink-700">89px</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 border-t border-ink-900/10 pt-10">
            <div class="meta-stamp text-ink-700">Dokumen</div>
            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-ink-700">
                <div><span class="font-mono uppercase tracking-wider text-xs text-ink-900">Moodboard</span> · docs/design-system/moodboard.md</div>
                <div><span class="font-mono uppercase tracking-wider text-xs text-ink-900">Style Guide</span> · docs/design-system/style-guide.md</div>
                <div><span class="font-mono uppercase tracking-wider text-xs text-ink-900">Mockups</span> · docs/design-system/mockups.md</div>
                <div><span class="font-mono uppercase tracking-wider text-xs text-ink-900">Persona</span> · docs/design-system/personas.md</div>
                <div><span class="font-mono uppercase tracking-wider text-xs text-ink-900">User Journey</span> · docs/design-system/user-journey.md</div>
                <div><span class="font-mono uppercase tracking-wider text-xs text-ink-900">Teknis</span> · docs/design-system/tech-implementation.md</div>
            </div>
        </div>
    </div>
</div>
@endsection
