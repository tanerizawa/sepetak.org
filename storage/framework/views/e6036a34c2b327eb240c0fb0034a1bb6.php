<?php $__env->startSection('title', ($code ?? 'Error') . ' — ' . \App\Models\SiteSetting::getValue('site_name', 'SEPETAK')); ?>
<?php $__env->startSection('meta_description', $message ?? 'Terjadi kesalahan.'); ?>

<?php $__env->startSection('content'); ?>
<section class="bg-paper-50 min-h-[70vh] flex items-center border-b-4 border-ink-900 grain-overlay">
    <div class="max-w-4xl mx-auto px-6 py-20 w-full">
        <div class="meta-stamp flex items-center gap-3 mb-6">
            <span class="inline-block h-[3px] w-10 bg-flag-500"></span>
            Kesalahan Sistem · Kode <?php echo e($code ?? '???'); ?>

        </div>

        <h1 class="font-display text-[clamp(5rem,16vw,11rem)] leading-[0.85] uppercase text-ink-900">
            <?php echo e($code ?? 'ERR'); ?>

        </h1>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-[1fr_auto] gap-8 items-end">
            <div>
                <h2 class="font-display text-3xl sm:text-4xl uppercase text-flag-600 mb-3">
                    <?php echo e($heading ?? 'Halaman Tidak Ditemukan'); ?>

                </h2>
                <p class="text-ink-700 text-lg max-w-2xl leading-relaxed">
                    <?php echo e($message ?? 'Dokumen yang Anda cari tidak tersedia atau telah dipindahkan.'); ?>

                </p>
            </div>
            <div class="flex flex-col gap-3">
                <?php if (isset($component)) { $__componentOriginalb249430bb893250886e66a0e6eefde94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb249430bb893250886e66a0e6eefde94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.btn','data' => ['href' => route('beranda'),'variant' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.btn'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('beranda')),'variant' => 'red']); ?>
                    <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'arrow-left','size' => '16']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-left','size' => '16']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc)): ?>
<?php $attributes = $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc; ?>
<?php unset($__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6abde2f60cd7ab2be425b8bf443880bc)): ?>
<?php $component = $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc; ?>
<?php unset($__componentOriginal6abde2f60cd7ab2be425b8bf443880bc); ?>
<?php endif; ?>
                    Kembali ke Beranda
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb249430bb893250886e66a0e6eefde94)): ?>
<?php $attributes = $__attributesOriginalb249430bb893250886e66a0e6eefde94; ?>
<?php unset($__attributesOriginalb249430bb893250886e66a0e6eefde94); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb249430bb893250886e66a0e6eefde94)): ?>
<?php $component = $__componentOriginalb249430bb893250886e66a0e6eefde94; ?>
<?php unset($__componentOriginalb249430bb893250886e66a0e6eefde94); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalb249430bb893250886e66a0e6eefde94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb249430bb893250886e66a0e6eefde94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.btn','data' => ['href' => route('posts.index'),'variant' => 'ghost']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.btn'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('posts.index')),'variant' => 'ghost']); ?>
                    Baca Artikel
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb249430bb893250886e66a0e6eefde94)): ?>
<?php $attributes = $__attributesOriginalb249430bb893250886e66a0e6eefde94; ?>
<?php unset($__attributesOriginalb249430bb893250886e66a0e6eefde94); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb249430bb893250886e66a0e6eefde94)): ?>
<?php $component = $__componentOriginalb249430bb893250886e66a0e6eefde94; ?>
<?php unset($__componentOriginalb249430bb893250886e66a0e6eefde94); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sepetak.org/resources/views/errors/layout.blade.php ENDPATH**/ ?>