{{-- Marka logo persegi (lencana) — berkas di public/img/logo. --}}
@php
    $v = (string) config('sepetak.logo_asset_version', '3');
@endphp
<img
    src="/img/logo/logo-64.png?v={{ $v }}"
    srcset="/img/logo/logo-48.png?v={{ $v }} 48w, /img/logo/logo-64.png?v={{ $v }} 64w, /img/logo/logo-128.png?v={{ $v }} 128w"
    sizes="40px"
    alt="{{ $alt ?? 'Logo SEPETAK' }}"
    width="40"
    height="40"
    class="{{ $class ?? 'block w-full h-full object-cover' }}"
    decoding="async"
>
