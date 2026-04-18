@extends('layouts.app')

@section('title', 'Pendaftaran Anggota — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))
@section('meta_description', 'Bergabunglah menjadi anggota SEPETAK untuk memperkuat perjuangan reforma agraria dan hak-hak petani Karawang.')

@push('styles')
<style>
    .field-rev { display: flex; flex-direction: column; gap: 0.375rem; }
    .field-rev > label {
        font-family: Anton, sans-serif;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #0D0D0D;
    }
    .field-rev .req { color: #C8102E; }
    .input-rev {
        width: 100%;
        padding: 0.65rem 0.85rem;
        background: #FCF9F1;
        border: 3px solid #0D0D0D;
        border-radius: 0;
        color: #0D0D0D;
        font-family: "Work Sans", sans-serif;
        font-size: 0.95rem;
        transition: box-shadow 120ms ease, transform 120ms ease;
    }
    .input-rev:focus {
        outline: none;
        box-shadow: 4px 4px 0 #C8102E;
        transform: translate(-2px, -2px);
    }
    select.input-rev { appearance: none; background-image: linear-gradient(45deg, transparent 50%, #0D0D0D 50%), linear-gradient(135deg, #0D0D0D 50%, transparent 50%); background-position: calc(100% - 18px) 50%, calc(100% - 12px) 50%; background-size: 6px 6px, 6px 6px; background-repeat: no-repeat; padding-right: 2.25rem; }
    textarea.input-rev { resize: vertical; min-height: 6rem; }
</style>
@endpush

@section('content')

{{-- Masthead --}}
<section class="bg-paper-50 border-b-4 border-ink-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <nav class="meta-stamp mb-5 flex items-center gap-2">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span class="text-flag-500">//</span>
            <span>Pendaftaran</span>
        </nav>
        <div class="grid grid-cols-1 md:grid-cols-[1.3fr_1fr] gap-8 items-end">
            <div>
                <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[0.9] uppercase text-ink-900">
                    Satu Tanda Tangan,<br>
                    <span class="text-flag-600">Satu Kepalan.</span>
                </h1>
                <p class="mt-5 text-ink-700 text-lg leading-relaxed max-w-xl">
                    Formulir ini adalah gerbang pertama Anda masuk ke barisan Serikat Pekerja Tani Karawang. Isi dengan jujur dan tegas — seperti Anda berdiri di hadapan kawan-kawan sesama petani.
                </p>
            </div>
            <div class="border-4 border-ink-900 bg-flag-500 text-paper-50 p-6 shadow-poster">
                <div class="font-mono text-[0.65rem] uppercase tracking-widest mb-2 text-paper-100">Formulir Resmi № 01</div>
                <div class="font-display text-3xl leading-none uppercase">Permohonan Keanggotaan</div>
                <div class="mt-3 flex items-center gap-2 font-mono text-xs uppercase tracking-widest text-paper-100">
                    <x-rev.icon name="signature" size="16" class="text-paper-50"/>
                    Diterbitkan Sekretariat
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Form --}}
<section class="py-14 bg-paper-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($errors->any())
            <div class="mb-8 border-4 border-flag-500 bg-paper-100 p-5 shadow-poster-sm">
                <div class="flex items-center gap-2 font-display uppercase tracking-widest text-flag-600 mb-3">
                    <x-rev.icon name="megaphone" size="20"/>
                    Ada Kesalahan Pengisian
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 text-ink-900 font-mono">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('member-registration.store') }}" method="POST" class="bg-paper-50 border-4 border-ink-900 shadow-poster p-6 sm:p-10 space-y-10">
            @csrf
            @honeypot

            {{-- Data Pribadi --}}
            <fieldset>
                <legend class="font-display text-2xl uppercase tracking-wider border-b-4 border-flag-500 pb-2 mb-1">01 — Data Pribadi</legend>
                <p class="meta-stamp mb-6 mt-2 text-ink-700">Lengkapi informasi dasar diri Anda.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2 field-rev">
                        <label for="full_name">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="full_name" id="full_name" required value="{{ old('full_name') }}" class="input-rev">
                    </div>

                    <div class="field-rev">
                        <label for="gender">Jenis Kelamin <span class="req">*</span></label>
                        <select name="gender" id="gender" required class="input-rev">
                            <option value="">-- Pilih --</option>
                            <option value="male" @selected(old('gender') === 'male')>Laki-laki</option>
                            <option value="female" @selected(old('gender') === 'female')>Perempuan</option>
                            <option value="other" @selected(old('gender') === 'other')>Lainnya</option>
                        </select>
                    </div>

                    <div class="field-rev">
                        <label for="birth_date">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="input-rev">
                    </div>
                </div>
            </fieldset>

            {{-- Kontak --}}
            <fieldset>
                <legend class="font-display text-2xl uppercase tracking-wider border-b-4 border-flag-500 pb-2 mb-1">02 — Kontak</legend>
                <p class="meta-stamp mb-6 mt-2 text-ink-700">Agar kami dapat menghubungi Anda untuk proses verifikasi.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="field-rev">
                        <label for="phone">Telepon / WhatsApp</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="input-rev">
                    </div>
                    <div class="field-rev">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@contoh.com" class="input-rev">
                    </div>
                </div>
            </fieldset>

            {{-- Alamat --}}
            <fieldset>
                <legend class="font-display text-2xl uppercase tracking-wider border-b-4 border-flag-500 pb-2 mb-1">03 — Alamat Tempat Tinggal</legend>
                <p class="meta-stamp mb-6 mt-2 text-ink-700">Sebutkan lokasi Anda agar kami mengelompokkan anggota per basis.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2 field-rev">
                        <label for="address_line_1">Jalan / RT / RW</label>
                        <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1') }}" class="input-rev">
                    </div>
                    <div class="field-rev">
                        <label for="address_village">Desa / Kelurahan</label>
                        <input type="text" name="address_village" id="address_village" value="{{ old('address_village') }}" class="input-rev">
                    </div>
                    <div class="field-rev">
                        <label for="address_district">Kecamatan</label>
                        <input type="text" name="address_district" id="address_district" value="{{ old('address_district') }}" class="input-rev">
                    </div>
                    <div class="sm:col-span-2 field-rev">
                        <label for="address_regency">Kabupaten / Kota</label>
                        <input type="text" name="address_regency" id="address_regency" value="{{ old('address_regency', 'Karawang') }}" class="input-rev">
                    </div>
                </div>
            </fieldset>

            {{-- Catatan --}}
            <fieldset>
                <legend class="font-display text-2xl uppercase tracking-wider border-b-4 border-flag-500 pb-2 mb-1">04 — Catatan</legend>
                <p class="meta-stamp mb-6 mt-2 text-ink-700">Ceritakan alasan Anda bergabung atau informasi penting lain (opsional).</p>
                <div class="field-rev">
                    <label for="notes" class="sr-only">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="5" class="input-rev">{{ old('notes') }}</textarea>
                </div>
            </fieldset>

            {{-- Submit --}}
            <div class="pt-6 border-t-4 border-ink-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <p class="meta-stamp text-ink-700 max-w-md leading-relaxed">
                    Dengan mendaftar, Anda menyetujui data digunakan untuk keperluan keanggotaan SEPETAK dan dijaga kerahasiaannya.
                </p>
                <button type="submit" class="btn-rev btn-rev-red">
                    <x-rev.icon name="signature" size="18"/>
                    Kirim Pendaftaran
                </button>
            </div>
        </form>

        <div class="mt-10 border-4 border-ink-900 bg-paper-100 p-6 grain-overlay">
            <div class="font-display uppercase tracking-widest text-flag-600 mb-2 flex items-center gap-2">
                <x-rev.icon name="megaphone" size="18"/>
                Langkah Selanjutnya
            </div>
            <p class="text-sm leading-relaxed text-ink-700">
                Sekretariat akan menghubungi Anda untuk verifikasi data. Status keanggotaan aktif setelah verifikasi selesai. Jika ada pertanyaan, hubungi
                <a href="mailto:{{ \App\Models\SiteSetting::getValue('contact_email', 'info@sepetak.org') }}" class="underline font-semibold text-flag-600 hover:bg-flag-500 hover:text-paper-50">
                    {{ \App\Models\SiteSetting::getValue('contact_email', 'info@sepetak.org') }}
                </a>.
            </p>
        </div>
    </div>
</section>

@endsection
