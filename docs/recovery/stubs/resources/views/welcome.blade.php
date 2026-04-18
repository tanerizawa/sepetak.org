<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEPETAK</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- @vite throws 500 if public/build/manifest.json is missing on deploy; keep beranda reachable. --}}
    @if (file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif (is_readable(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            *,*::before,*::after{box-sizing:border-box}
            body{margin:0}
        </style>
    @endif
</head>
<body class="min-h-screen bg-stone-950 text-stone-100" style="font-family: 'Instrument Sans', sans-serif;">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(101,163,13,0.28),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(245,158,11,0.2),_transparent_28%),linear-gradient(135deg,_#0c0a09,_#14532d_45%,_#1c1917)]"></div>
        <div class="absolute inset-0 opacity-20 [background-image:linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] [background-size:40px_40px]"></div>

        <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-6 py-8 lg:px-10">
            <header class="flex items-center justify-between border-b border-white/10 pb-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-lime-300">SEPETAK</p>
                    <h1 class="mt-2 text-lg font-semibold text-white">Serikat Pekerja Tani Karawang</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="/admin/login" class="rounded-full border border-lime-300/40 px-4 py-2 text-sm font-medium text-lime-100 transition hover:border-lime-200 hover:bg-lime-300/10">Masuk Admin</a>
                </div>
            </header>

            <main class="flex flex-1 items-center py-12 lg:py-20">
                <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1.2fr)_minmax(22rem,0.8fr)] lg:gap-12">
                    <section>
                        <div class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.24em] text-stone-200">
                            Fondasi aplikasi sudah aktif
                        </div>

                        <h2 class="mt-6 max-w-4xl text-4xl font-semibold leading-tight text-white sm:text-5xl lg:text-6xl">
                            Basis web organisasi dan panel admin SEPETAK sudah berhasil dibootstrap.
                        </h2>

                        <p class="mt-6 max-w-2xl text-base leading-8 text-stone-200/85 sm:text-lg">
                            Repo ini sudah berjalan di Laravel 11 dengan Filament admin panel, siap dilanjutkan ke modul anggota, kasus agraria, advokasi, artikel, dan website publik MVP.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="/admin" class="rounded-full bg-lime-300 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-lime-200">Buka Panel Admin</a>
                            <a href="/admin/login" class="rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/30 hover:bg-white/5">Login Admin</a>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.2em] text-lime-300">Stack</p>
                                <p class="mt-3 text-lg font-semibold text-white">Laravel 11 + Filament 3</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.2em] text-lime-300">Status</p>
                                <p class="mt-3 text-lg font-semibold text-white">App base aktif</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.2em] text-lime-300">Lanjut</p>
                                <p class="mt-3 text-lg font-semibold text-white">Migrasi + admin user</p>
                            </div>
                        </div>
                    </section>

                    <aside class="rounded-[2rem] border border-white/10 bg-stone-950/45 p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-300">Status Teknis</p>

                        <div class="mt-6 space-y-4 text-sm text-stone-200">
                            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
                                <p class="font-semibold text-white">Selesai diverifikasi</p>
                                <ul class="mt-3 space-y-2 leading-7 text-stone-300">
                                    <li>Laravel berhasil boot</li>
                                    <li>Filament panel berhasil terpasang</li>
                                    <li>Assets frontend berhasil dibuild</li>
                                    <li>Route admin login sudah aktif di <span class="font-semibold text-white">/admin/login</span></li>
                                </ul>
                            </div>

                            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
                                <p class="font-semibold text-white">Blocker saat ini</p>
                                <p class="mt-3 leading-7 text-stone-300">
                                    Migrasi database dan pembuatan user admin belum dijalankan karena toolchain MySQL belum tersedia di environment ini dan SQLite driver PHP tidak aktif.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/8 bg-lime-300/10 p-4 text-lime-50">
                                <p class="font-semibold">Target tahap berikutnya</p>
                                <p class="mt-3 leading-7 text-lime-50/90">
                                    Aktivasi koneksi database, jalankan migration, lalu buat resource Filament inti: anggota, kasus agraria, advokasi, artikel, dan kegiatan.
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
