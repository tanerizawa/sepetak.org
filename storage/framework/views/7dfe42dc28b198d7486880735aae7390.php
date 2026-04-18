<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', \App\Models\SiteSetting::getValue('site_description', 'SEPETAK - Serikat Pekerja Tani Karawang')); ?>">
    <meta name="theme-color" content="#C8102E">
    <title><?php echo $__env->yieldContent('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK')); ?></title>

    
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <link rel="alternate" type="application/rss+xml" title="SEPETAK RSS" href="<?php echo e(url('/feed.xml')); ?>">

    
    <meta property="og:type" content="<?php echo $__env->yieldContent('og_type', 'website'); ?>">
    <meta property="og:site_name" content="<?php echo e(\App\Models\SiteSetting::getValue('site_name', 'SEPETAK')); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:title" content="<?php echo $__env->yieldContent('og_title', $__env->yieldContent('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og_description', $__env->yieldContent('meta_description', \App\Models\SiteSetting::getValue('site_description', 'SEPETAK - Serikat Pekerja Tani Karawang'))); ?>">
    <meta property="og:locale" content="id_ID">

    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('og_title', $__env->yieldContent('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK'))); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('og_description', $__env->yieldContent('meta_description', \App\Models\SiteSetting::getValue('site_description', 'SEPETAK - Serikat Pekerja Tani Karawang'))); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&family=Roboto+Slab:wght@400;600&display=swap" rel="stylesheet">

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php elseif(is_readable(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php echo $__env->yieldPushContent('styles'); ?>

    
    <?php echo $__env->make('partials.jsonld.organization', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="bg-paper-50 text-ink-900 antialiased">

    
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[100] focus:bg-flag-500 focus:text-paper-50 focus:px-4 focus:py-2 focus:font-display focus:tracking-widest">Lewati ke konten</a>

    
    <div class="bg-flag-500 text-paper-50 border-b-4 border-ink-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-9 text-xs">
            <span class="font-mono tracking-widest uppercase hidden sm:inline">SOLIDARITAS · ORGANISASI · PEMBEBASAN</span>
            <span class="font-mono tracking-widest uppercase sm:hidden">TANI MERAH</span>
            <div class="flex items-center gap-4 font-mono tracking-wider uppercase">
                <a href="<?php echo e(route('pages.show', 'kontak')); ?>" class="hover:underline">Kontak</a>
                <span class="opacity-50">|</span>
                <a href="<?php echo e(url('/feed.xml')); ?>" class="hover:underline">RSS</a>
            </div>
        </div>
    </div>

    
    <nav class="bg-paper-50 border-b-4 border-ink-900 sticky top-0 z-50 shadow-[0_4px_0_#C8102E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <a href="<?php echo e(route('beranda')); ?>" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-flag-500 border-2 border-ink-900 flex items-center justify-center group-hover:bg-ink-900 group-hover:text-flag-500 transition-colors">
                        <span class="font-display text-paper-50 text-xl leading-none group-hover:text-flag-500">S</span>
                    </div>
                    <div class="leading-none">
                        <div class="font-display text-xl text-ink-900 tracking-wider">SEPETAK</div>
                        <div class="font-mono text-[0.6rem] uppercase tracking-widest text-ink-700">Tani Karawang</div>
                    </div>
                </a>

                
                <div class="hidden md:flex items-center gap-7">
                    <a href="<?php echo e(route('beranda')); ?>" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Beranda</a>
                    <a href="<?php echo e(route('posts.index')); ?>" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Berita</a>
                    <a href="<?php echo e(route('pages.show', 'tentang-kami')); ?>" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Tentang</a>
                    <a href="<?php echo e(route('pages.show', 'sejarah')); ?>" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Sejarah</a>
                    <a href="<?php echo e(route('pages.show', 'struktur-organisasi')); ?>" class="font-display uppercase tracking-widest text-sm text-ink-900 hover:text-flag-600 border-b-2 border-transparent hover:border-flag-500 pb-1 transition-colors">Struktur</a>
                    <?php if (isset($component)) { $__componentOriginalb249430bb893250886e66a0e6eefde94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb249430bb893250886e66a0e6eefde94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.btn','data' => ['href' => route('member-registration.create'),'variant' => 'red','class' => '!py-2.5 !px-5 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.btn'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('member-registration.create')),'variant' => 'red','class' => '!py-2.5 !px-5 text-xs']); ?>
                        Daftar Anggota
                        <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'arrow-right','size' => '16']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','size' => '16']); ?>
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

                
                <button id="mobile-menu-btn" aria-label="Buka menu" aria-expanded="false" class="md:hidden p-2 border-2 border-ink-900 bg-paper-50 text-ink-900 hover:bg-ink-900 hover:text-flag-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t-2 border-ink-900/20 pt-2 space-y-1">
                <a href="<?php echo e(route('beranda')); ?>" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Beranda</a>
                <a href="<?php echo e(route('posts.index')); ?>" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Berita</a>
                <a href="<?php echo e(route('pages.show', 'tentang-kami')); ?>" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Tentang Kami</a>
                <a href="<?php echo e(route('pages.show', 'sejarah')); ?>" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Sejarah</a>
                <a href="<?php echo e(route('pages.show', 'struktur-organisasi')); ?>" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Struktur</a>
                <a href="<?php echo e(route('pages.show', 'wilayah-kerja')); ?>" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Wilayah Kerja</a>
                <a href="<?php echo e(route('pages.show', 'kontak')); ?>" class="block py-2 font-display uppercase tracking-widest text-sm text-ink-900 hover:bg-flag-500 hover:text-paper-50 px-2">Kontak</a>
                <a href="<?php echo e(route('member-registration.create')); ?>" class="mt-3 block btn-rev btn-rev-red w-full justify-center">
                    Daftar Anggota
                </a>
            </div>
        </div>
    </nav>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="border-4 border-ink-900 bg-paper-100 text-ink-900 px-4 py-3 flex items-center gap-3 shadow-poster-sm">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-flag-500 text-paper-50">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </span>
            <span class="font-mono tracking-wide uppercase text-sm"><?php echo e(session('success')); ?></span>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="border-4 border-flag-500 bg-paper-50 text-ink-900 px-4 py-3 flex items-center gap-3 shadow-poster-sm">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-flag-500 text-paper-50">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </span>
            <span class="font-mono tracking-wide uppercase text-sm"><?php echo e(session('error')); ?></span>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <main id="main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="bg-ink-900 text-paper-50 mt-20 border-t-8 border-flag-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            
            <div class="mb-12 pb-8 border-b-2 border-paper-50/20">
                <div class="font-display text-4xl sm:text-6xl leading-[0.9] text-paper-50 tracking-wide">
                    TANAH UNTUK <span class="text-flag-500">PENGGARAPNYA</span>.
                </div>
                <p class="mt-3 font-mono tracking-widest uppercase text-xs text-paper-200">
                    Sejak 1999 — Serikat Pekerja Tani Karawang
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-flag-500 flex items-center justify-center">
                            <span class="font-display text-paper-50 text-xl leading-none">S</span>
                        </div>
                        <div>
                            <div class="font-display text-xl tracking-wider">SEPETAK</div>
                            <div class="font-mono text-[0.6rem] uppercase tracking-widest text-paper-200">Serikat Pekerja Tani Karawang</div>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed text-paper-200 max-w-md">
                        <?php echo e(\App\Models\SiteSetting::getValue('site_tagline', 'Pekerja Tani Soko Guru Pembebasan — berjuang bersama untuk reforma agraria, keadilan sosial, dan kedaulatan pangan di Karawang.')); ?>

                    </p>
                </div>

                <div>
                    <h4 class="font-display text-lg tracking-widest text-flag-500 mb-4 uppercase">Navigasi</h4>
                    <ul class="space-y-2 text-sm font-mono uppercase tracking-wider">
                        <li><a href="<?php echo e(route('beranda')); ?>" class="text-paper-100 hover:text-flag-400 hover:underline">Beranda</a></li>
                        <li><a href="<?php echo e(route('posts.index')); ?>" class="text-paper-100 hover:text-flag-400 hover:underline">Berita</a></li>
                        <li><a href="<?php echo e(route('pages.show', 'tentang-kami')); ?>" class="text-paper-100 hover:text-flag-400 hover:underline">Tentang</a></li>
                        <li><a href="<?php echo e(route('pages.show', 'visi-misi')); ?>" class="text-paper-100 hover:text-flag-400 hover:underline">Visi &amp; Misi</a></li>
                        <li><a href="<?php echo e(route('pages.show', 'sejarah')); ?>" class="text-paper-100 hover:text-flag-400 hover:underline">Sejarah</a></li>
                        <li><a href="<?php echo e(route('pages.show', 'struktur-organisasi')); ?>" class="text-paper-100 hover:text-flag-400 hover:underline">Struktur</a></li>
                        <li><a href="<?php echo e(route('pages.show', 'wilayah-kerja')); ?>" class="text-paper-100 hover:text-flag-400 hover:underline">Wilayah Kerja</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-display text-lg tracking-widest text-flag-500 mb-4 uppercase">Sekretariat</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'signature','size' => '18','class' => 'mt-0.5 text-flag-500 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'signature','size' => '18','class' => 'mt-0.5 text-flag-500 flex-shrink-0']); ?>
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
                            <span><?php echo e(\App\Models\SiteSetting::getValue('contact_email', 'info@sepetak.org')); ?></span>
                        </li>
                        <?php ($contactPhone = \App\Models\SiteSetting::getValue('contact_phone')); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($contactPhone) && $contactPhone !== '+62 xxx xxxx xxxx'): ?>
                        <li class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'megaphone','size' => '18','class' => 'mt-0.5 text-flag-500 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'megaphone','size' => '18','class' => 'mt-0.5 text-flag-500 flex-shrink-0']); ?>
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
                            <span><?php echo e($contactPhone); ?></span>
                        </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <li class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'home','size' => '18','class' => 'mt-0.5 text-flag-500 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'home','size' => '18','class' => 'mt-0.5 text-flag-500 flex-shrink-0']); ?>
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
                            <span><?php echo e(\App\Models\SiteSetting::getValue('contact_address', 'Karawang, Jawa Barat')); ?></span>
                        </li>
                        <li class="mt-4">
                            <a href="<?php echo e(route('member-registration.create')); ?>" class="btn-rev btn-rev-red !py-2 !px-4 text-xs">
                                Bergabung
                                <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'arrow-right','size' => '14']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','size' => '14']); ?>
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
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t-2 border-paper-50/20 mt-12 pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs font-mono uppercase tracking-widest text-paper-200">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(\App\Models\SiteSetting::getValue('site_name', 'SEPETAK')); ?>. Solidaritas tanpa syarat.</p>
                <p><a href="<?php echo e(url('/feed.xml')); ?>" class="hover:text-flag-400">RSS Feed</a> · <a href="<?php echo e(url('/sitemap.xml')); ?>" class="hover:text-flag-400">Sitemap</a></p>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            var btn = document.getElementById('mobile-menu-btn');
            var menu = document.getElementById('mobile-menu');
            if (!btn || !menu) return;
            btn.addEventListener('click', function () {
                var hidden = menu.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', hidden ? 'false' : 'true');
            });
        })();
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/sepetak.org/resources/views/layouts/app.blade.php ENDPATH**/ ?>