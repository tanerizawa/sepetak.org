<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'width' => '100%',
    'height' => 'auto',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'width' => '100%',
    'height' => 'auto',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>



<svg
    <?php echo e($attributes->class('block w-full h-full')); ?>

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

    
    <path d="M0 330 L120 290 L240 320 L360 275 L480 305 L600 265 L720 300 L800 285 L800 360 L0 360 Z"
          fill="#7E0A1E" opacity="0.55"/>
    <path d="M0 360 L140 330 L280 355 L420 320 L560 345 L700 325 L800 340 L800 400 L0 400 Z"
          fill="#590815" opacity="0.75"/>

    
    <rect x="0" y="380" width="800" height="30" fill="#6B4423" opacity="0.2"/>
    <g transform="translate(110 388)">
        <path d="M-18 6 L18 6 L12 14 L-12 14 Z" fill="#0D0D0D"/>
        <path d="M0 -12 L0 6 M0 -12 L10 4" stroke="#0D0D0D" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    </g>

    
    <g>
        <rect x="0" y="410" width="800" height="26" fill="#D4A017"/>
        <rect x="0" y="436" width="800" height="22" fill="#B0841B"/>
        <rect x="0" y="458" width="800" height="22" fill="#8B691A"/>
        <rect x="0" y="480" width="800" height="26" fill="#6B4423"/>
        <rect x="0" y="506" width="800" height="30" fill="#513217"/>
        <rect x="0" y="536" width="800" height="64" fill="#0D0D0D"/>
    </g>

    
    <g stroke="#0D0D0D" stroke-width="1" opacity="0.4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 80; $i++): ?>
            <?php $x = $i * 10 + 5; ?>
            <line x1="<?php echo e($x); ?>" y1="412" x2="<?php echo e($x); ?>" y2="432"/>
        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </g>

    
    <g transform="translate(480 420) scale(1.35)" fill="#0D0D0D">
        
        <path d="M-34 -96 L34 -96 L16 -108 L-16 -108 Z"/>
        <ellipse cx="0" cy="-95" rx="36" ry="5"/>
        
        <circle cx="0" cy="-82" r="10"/>
        <rect x="-4" y="-74" width="8" height="8"/>
        
        <path d="M-22 -66 L22 -66 L26 -20 L-26 -20 Z"/>
        
        <path d="M10 -64 L34 -90 L40 -84 L16 -58 Z"/>
        
        <path d="M-22 -62 L-28 -22 L-20 -22 L-14 -60 Z"/>
        
        <rect x="34" y="-150" width="5" height="70" transform="rotate(18 36 -115)"/>
        
        <path d="M50 -152 L68 -146 L64 -136 L46 -142 Z"/>
        
        <rect x="-18" y="-20" width="12" height="40"/>
        <rect x="6" y="-20" width="12" height="40"/>
        
        <rect x="-22" y="18" width="18" height="6"/>
        <rect x="4" y="18" width="18" height="6"/>
    </g>

    
    <g transform="translate(220 460) scale(0.9)" fill="#1A1A1A">
        <path d="M-24 -70 L24 -70 L12 -80 L-12 -80 Z"/>
        <circle cx="0" cy="-60" r="8"/>
        <path d="M-16 -46 L16 -46 L20 -8 L-20 -8 Z"/>
        
        <ellipse cx="0" cy="-80" rx="20" ry="5" fill="#B0841B"/>
        <path d="M-18 -84 L18 -84 L14 -78 L-14 -78 Z" fill="#8B691A"/>
        
        <rect x="-12" y="-8" width="8" height="26"/>
        <rect x="4" y="-8" width="8" height="26"/>
    </g>

    
    <g transform="translate(660 450)">
        <rect x="0" y="-60" width="3" height="60" fill="#0D0D0D"/>
        <path d="M3 -60 L40 -54 L32 -48 L40 -42 L3 -36 Z" fill="#C8102E" stroke="#0D0D0D" stroke-width="2"/>
    </g>
</svg>
<?php /**PATH /home/sepetak.org/resources/views/components/rev/landscape-hero.blade.php ENDPATH**/ ?>