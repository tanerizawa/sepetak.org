@props([
    'width' => '100%',
    'height' => 'auto',
])

{{--
    Ilustrasi landscape SEPETAK "Tani Merah".
    Motif: sawah Karawang + pesisir utara + bukit Purwakarta + siluet petani heroik.
    Stil: realisme sosialis — figur heroik low-angle, stroke flat tegas, 3 warna
    (ink/flag/ochre) di atas paper. Spec lengkap di docs/LANDING_REDESIGN_PLAN.md §5.4.

    Dibuat full inline SVG (zero asset HTTP) agar cepat dan accessible —
    aria-label di bawah mendeskripsikan adegan secara semantik.
--}}

<svg
    {{ $attributes->class('block w-full h-full') }}
    viewBox="0 0 800 600"
    preserveAspectRatio="xMidYMax slice"
    xmlns="http://www.w3.org/2000/svg"
    role="img"
    aria-labelledby="landscape-hero-title landscape-hero-desc"
>
    <title id="landscape-hero-title">Pemandangan Karawang: sawah, pesisir, dan petani</title>
    <desc id="landscape-hero-desc">
        Ilustrasi gaya realisme sosialis: siluet seorang pekerja tani memanggul cangkul dengan sudut
        pandang rendah di atas pematang sawah, matahari terbit besar di belakang bukit Purwakarta,
        barisan padi yang dipanen, dan satu perahu nelayan kecil di garis pesisir utara.
    </desc>

    {{-- Langit paper dengan gradient lembut --}}
    <defs>
        <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#FCF9F1"/>
            <stop offset="70%" stop-color="#F4EEDB"/>
            <stop offset="100%" stop-color="#E7DDB7"/>
        </linearGradient>
        <linearGradient id="rice" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#D4A017"/>
            <stop offset="100%" stop-color="#8B691A"/>
        </linearGradient>
    </defs>

    <rect width="800" height="600" fill="url(#sky)"/>

    {{-- Matahari propaganda: lingkaran solid + 12 sinar horizontal --}}
    <g transform="translate(560 220)">
        <g stroke="#C8102E" stroke-width="4" opacity="0.85">
            <line x1="-260" y1="-80" x2="260" y2="-80"/>
            <line x1="-260" y1="-56" x2="260" y2="-56"/>
            <line x1="-260" y1="-32" x2="260" y2="-32"/>
            <line x1="-260" y1="-8"  x2="260" y2="-8"/>
            <line x1="-260" y1="16"  x2="260" y2="16"/>
            <line x1="-260" y1="40"  x2="260" y2="40"/>
            <line x1="-260" y1="64"  x2="260" y2="64"/>
            <line x1="-260" y1="88"  x2="260" y2="88"/>
        </g>
        <circle r="90" fill="#C8102E"/>
        <circle r="90" fill="none" stroke="#0D0D0D" stroke-width="4"/>
    </g>

    {{-- Bukit belakang (Purwakarta), dua lapis siluet --}}
    <path d="M0 330 L120 290 L240 320 L360 275 L480 305 L600 265 L720 300 L800 285 L800 360 L0 360 Z"
          fill="#7E0A1E" opacity="0.55"/>
    <path d="M0 360 L140 330 L280 355 L420 320 L560 345 L700 325 L800 340 L800 400 L0 400 Z"
          fill="#590815" opacity="0.75"/>

    {{-- Garis pesisir + perahu nelayan kecil (motif sekunder) --}}
    <rect x="0" y="380" width="800" height="30" fill="#6B4423" opacity="0.2"/>
    <g transform="translate(110 388)">
        <path d="M-18 6 L18 6 L12 14 L-12 14 Z" fill="#0D0D0D"/>
        <path d="M0 -12 L0 6 M0 -12 L10 4" stroke="#0D0D0D" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    </g>

    {{-- Sawah bergelombang (bands horizontal khas realisme sosialis) --}}
    <g>
        <rect x="0" y="410" width="800" height="26" fill="#D4A017"/>
        <rect x="0" y="436" width="800" height="22" fill="#B0841B"/>
        <rect x="0" y="458" width="800" height="22" fill="#8B691A"/>
        <rect x="0" y="480" width="800" height="26" fill="#6B4423"/>
        <rect x="0" y="506" width="800" height="30" fill="#513217"/>
        <rect x="0" y="536" width="800" height="64" fill="#0D0D0D"/>
    </g>

    {{-- Tekstur padi: garis-garis tipis vertikal di band emas (mengacu realism tradisi) --}}
    <g stroke="#0D0D0D" stroke-width="1" opacity="0.4">
        @for ($i = 0; $i < 80; $i++)
            @php $x = $i * 10 + 5; @endphp
            <line x1="{{ $x }}" y1="412" x2="{{ $x }}" y2="432"/>
        @endfor
    </g>

    {{--
        Siluet petani heroik di foreground (kanan-bawah), low-angle.
        Komposisi: badan tegak + cangkul dipanggul ke bahu kanan, topi caping lebar.
        Pakai fill ink tunggal agar membaca sebagai "figur monumen" dari jauh.
    --}}
    <g transform="translate(480 420) scale(1.35)" fill="#0D0D0D">
        {{-- Caping (topi kerucut lebar) --}}
        <path d="M-34 -96 L34 -96 L16 -108 L-16 -108 Z"/>
        <ellipse cx="0" cy="-95" rx="36" ry="5"/>
        {{-- Kepala + leher --}}
        <circle cx="0" cy="-82" r="10"/>
        <rect x="-4" y="-74" width="8" height="8"/>
        {{-- Badan + jaket tani --}}
        <path d="M-22 -66 L22 -66 L26 -20 L-26 -20 Z"/>
        {{-- Lengan kanan memanggul cangkul --}}
        <path d="M10 -64 L34 -90 L40 -84 L16 -58 Z"/>
        {{-- Lengan kiri menjuntai --}}
        <path d="M-22 -62 L-28 -22 L-20 -22 L-14 -60 Z"/>
        {{-- Pegangan cangkul panjang ke atas --}}
        <rect x="34" y="-150" width="5" height="70" transform="rotate(18 36 -115)"/>
        {{-- Mata cangkul --}}
        <path d="M50 -152 L68 -146 L64 -136 L46 -142 Z"/>
        {{-- Kaki + celana panjang --}}
        <rect x="-18" y="-20" width="12" height="40"/>
        <rect x="6" y="-20" width="12" height="40"/>
        {{-- Sepatu boot --}}
        <rect x="-22" y="18" width="18" height="6"/>
        <rect x="4" y="18" width="18" height="6"/>
    </g>

    {{-- Figur pendamping kecil di kiri — petani perempuan membawa bakul --}}
    <g transform="translate(220 460) scale(0.9)" fill="#1A1A1A">
        <path d="M-24 -70 L24 -70 L12 -80 L-12 -80 Z"/>
        <circle cx="0" cy="-60" r="8"/>
        <path d="M-16 -46 L16 -46 L20 -8 L-20 -8 Z"/>
        {{-- Bakul di atas kepala --}}
        <ellipse cx="0" cy="-80" rx="20" ry="5" fill="#B0841B"/>
        <path d="M-18 -84 L18 -84 L14 -78 L-14 -78 Z" fill="#8B691A"/>
        {{-- Kaki --}}
        <rect x="-12" y="-8" width="8" height="26"/>
        <rect x="4" y="-8" width="8" height="26"/>
    </g>

    {{-- Bendera merah kecil menancap di pematang (simbol organisasi) --}}
    <g transform="translate(660 450)">
        <rect x="0" y="-60" width="3" height="60" fill="#0D0D0D"/>
        <path d="M3 -60 L40 -54 L32 -48 L40 -42 L3 -36 Z" fill="#C8102E" stroke="#0D0D0D" stroke-width="2"/>
    </g>
</svg>
