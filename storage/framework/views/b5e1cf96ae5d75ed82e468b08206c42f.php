<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'sun',
    'size' => 24,
    'title' => null,
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
    'name' => 'sun',
    'size' => 24,
    'title' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<?php
    $paths = [
        // Sabit tani
        'sickle' => '<path d="M4 20 C8 10 14 4 20 4 M20 4 C18 8 14 12 8 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                     <rect x="2" y="18" width="6" height="4" fill="currentColor" rx="0"/>',
        // Cangkul
        'hoe'   => '<path d="M4 20 L20 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M14 4 L20 4 L20 10 Z" fill="currentColor"/>',
        // Padi
        'wheat' => '<path d="M12 22 V4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M12 8 L6 5 M12 12 L6 9 M12 16 L6 13 M12 8 L18 5 M12 12 L18 9 M12 16 L18 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        // Perahu nelayan
        'boat'  => '<path d="M2 16 H22 L20 20 H4 Z" fill="currentColor"/>
                    <path d="M12 4 V16 M12 4 L18 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none"/>',
        // Matahari bergaris
        'sun'   => '<circle cx="12" cy="12" r="4" fill="currentColor"/>
                    <path d="M12 3 V6 M12 18 V21 M3 12 H6 M18 12 H21 M5 5 L7 7 M17 17 L19 19 M5 19 L7 17 M17 7 L19 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>',
        // Megaphone (kampanye)
        'megaphone' => '<path d="M4 10 V14 L14 18 V6 Z" fill="currentColor"/>
                        <path d="M14 9 L20 6 V18 L14 15" stroke="currentColor" stroke-width="2.5" fill="none"/>',
        // Timbangan (advokasi hukum)
        'scales' => '<path d="M12 3 V21 M4 21 H20" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                     <path d="M4 7 H20 M7 7 L4 13 L10 13 Z M17 7 L14 13 L20 13 Z" stroke="currentColor" stroke-width="2" fill="none" stroke-linejoin="miter"/>',
        // Kepalan solidaritas (terbuka — bukan memegang senjata)
        'fist'  => '<rect x="6" y="9" width="12" height="10" fill="currentColor" rx="0"/>
                    <rect x="6" y="5" width="3" height="6" fill="currentColor"/>
                    <rect x="10" y="4" width="3" height="7" fill="currentColor"/>
                    <rect x="14" y="5" width="3" height="6" fill="currentColor"/>',
        // Tanda tangan (pendaftaran)
        'signature' => '<path d="M3 18 C7 10 10 10 12 14 C14 18 17 8 21 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                        <path d="M3 21 H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>',
        // Panah kanan (CTA)
        'arrow-right' => '<path d="M4 12 H20 M14 6 L20 12 L14 18" stroke="currentColor" stroke-width="3" stroke-linecap="square" fill="none"/>',
        // Rumah tani
        'home'  => '<path d="M3 10 L12 3 L21 10 V21 H3 Z" fill="currentColor"/>
                    <rect x="10" y="14" width="4" height="7" fill="#FCF9F1"/>',
    ];
    $path = $paths[$name] ?? $paths['sun'];
?>

<svg
    <?php echo e($attributes->except('class')->class($attributes->get('class') ?? '')); ?>

    width="<?php echo e($size); ?>"
    height="<?php echo e($size); ?>"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    <?php if($title): ?> role="img" aria-label="<?php echo e($title); ?>" <?php else: ?> aria-hidden="true" focusable="false" <?php endif; ?>
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($title): ?>
        <title><?php echo e($title); ?></title>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php echo $path; ?>

</svg>
<?php /**PATH /home/sepetak.org/resources/views/components/rev/icon.blade.php ENDPATH**/ ?>