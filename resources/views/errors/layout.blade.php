@extends('layouts.app')

@section('title', ($code ?? 'Error') . ' — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', $message ?? 'Terjadi kesalahan.')

@section('content')
<section class="bg-paper-50 min-h-[70vh] flex items-center border-b-4 border-ink-900 grain-overlay">
    <div class="max-w-4xl mx-auto px-6 py-20 w-full">
        <div class="meta-stamp flex items-center gap-3 mb-6">
            <span class="inline-block h-[3px] w-10 bg-flag-500"></span>
            Kesalahan Sistem · Kode {{ $code ?? '???' }}
        </div>

        <h1 class="font-display text-[clamp(5rem,16vw,11rem)] leading-[0.85] uppercase text-ink-900">
            {{ $code ?? 'ERR' }}
        </h1>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-[1fr_auto] gap-8 items-end">
            <div>
                <h2 class="font-display text-3xl sm:text-4xl uppercase text-flag-600 mb-3">
                    {{ $heading ?? 'Halaman Tidak Ditemukan' }}
                </h2>
                <p class="text-ink-700 text-lg max-w-2xl leading-relaxed">
                    {{ $message ?? 'Dokumen yang Anda cari tidak tersedia atau telah dipindahkan.' }}
                </p>
            </div>
            <div class="flex flex-col gap-3">
                <x-rev.btn :href="route('beranda')" variant="red">
                    <x-rev.icon name="arrow-left" size="16"/>
                    Kembali ke Beranda
                </x-rev.btn>
                <x-rev.btn :href="route('posts.index')" variant="ghost">
                    Baca Artikel
                </x-rev.btn>
            </div>
        </div>
    </div>
</section>
@endsection
