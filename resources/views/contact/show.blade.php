@extends('layouts.app')

@section('title', 'Kontak — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Kontak sekretariat Serikat Pekerja Tani Karawang: email, telepon, alamat, jalur pendaftaran anggota, pendampingan kasus agraria, media, dan solidaritas.')

@push('styles')
@include('partials.jsonld.breadcrumb', ['items' => [
    ['name' => 'Beranda', 'url' => route('beranda')],
    ['name' => 'Kontak', 'url' => url()->current()],
]])
@endpush

@php
    $email = \App\Models\SiteSetting::getValue('contact_email', 'info@sepetak.org');
    $phone = \App\Models\SiteSetting::getValue('contact_phone');
    $address = \App\Models\SiteSetting::getValue('contact_address');
@endphp

@section('content')

<section class="bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-5 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <span>Kontak</span>
        </nav>
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl leading-[0.9] uppercase text-ink-900">
            Kontak<br>
            <span class="text-flag-600">Sekretariat.</span>
        </h1>
        <div class="mt-5 flex items-center gap-3">
            <span class="inline-block h-[3px] w-20 bg-flag-500"></span>
            <span class="meta-stamp">Informasi resmi</span>
        </div>
        <p class="mt-5 text-ink-700 text-lg leading-relaxed max-w-2xl">
            Rincian jalur komunikasi dengan SEPETAK dan rujukan halaman organisasi.
        </p>
    </div>
</section>

<section class="bg-paper-100 py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        {{-- Kontak operasional (pengaturan situs) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="border-4 border-ink-900 bg-paper-50 p-5">
                <div class="meta-stamp text-flag-600 mb-2">Email</div>
                <a href="mailto:{{ $email }}" class="font-display text-lg uppercase text-ink-900 hover:underline break-all">{{ $email }}</a>
            </div>
            @if($phone)
            <div class="border-4 border-ink-900 bg-paper-50 p-5">
                <div class="meta-stamp text-flag-600 mb-2">Telepon / WhatsApp</div>
                <a href="tel:{{ preg_replace('/\D+/', '', $phone) }}" class="font-display text-lg uppercase text-ink-900 hover:underline">{{ $phone }}</a>
            </div>
            @endif
            @if($address)
            <div class="border-4 border-ink-900 bg-paper-50 p-5 sm:col-span-2 lg:col-span-1">
                <div class="meta-stamp text-flag-600 mb-2">Alamat sekretariat</div>
                <p class="text-ink-900 leading-relaxed whitespace-pre-line">{{ $address }}</p>
            </div>
            @endif
        </div>

        <article class="border-4 border-ink-900 bg-paper-50 p-6 sm:p-10 shadow-poster">
            <div class="prose-rev">
                {!! $detailBody !!}
            </div>
        </article>

        <div class="border-4 border-ink-900 bg-ink-900 text-paper-50 p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="meta-stamp text-flag-500 mb-2">Ingin bergabung?</div>
                <p class="text-sm leading-relaxed text-paper-100 max-w-xl">Jadilah bagian dari Serikat Pekerja Tani Karawang. Gunakan formulir pendaftaran anggota online.</p>
            </div>
            <a href="{{ route('member-registration.create') }}" class="btn-rev btn-rev-red shrink-0 self-start sm:self-center">
                <x-rev.icon name="signature" size="16"/>
                Daftar anggota
            </a>
        </div>
    </div>
</section>

@endsection
