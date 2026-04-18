<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [],
    'tone' => 'red', // red | ink | paper
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
    'items' => [],
    'tone' => 'red', // red | ink | paper
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $toneClass = match ($tone) {
        'ink' => 'bg-ink-900 text-paper-50 border-y-flag-500',
        'paper' => 'bg-paper-100 text-ink-900 border-y-ink-900',
        default => 'bg-flag-500 text-paper-50 border-y-ink-900',
    };

    // Double list supaya marquee loop terasa kontinu.
    $loop = array_merge($items, $items);
?>

<div <?php echo e($attributes->class('w-full overflow-hidden border-y-4 ' . $toneClass)); ?> role="marquee" aria-label="Slogan Tani Merah">
    <div class="ticker-track whitespace-nowrap py-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $loop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="inline-flex items-center gap-6 font-display text-xl tracking-widest uppercase">
                <span class="inline-block h-2 w-2 bg-current"></span>
                <?php echo e($text); ?>

            </span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH /home/sepetak.org/resources/views/components/rev/ticker.blade.php ENDPATH**/ ?>