<?php $__env->startSection('title', \App\Models\SiteSetting::getValue('site_name', 'SEPETAK') . ' — Pekerja Tani Soko Guru Pembebasan'); ?>

<?php $__env->startPush('head'); ?>
    <?php echo $__env->make('partials.jsonld.website', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="relative bg-paper-50 border-b-4 border-ink-900 overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_1fr] min-h-[560px] lg:min-h-[640px]">

        
        <div class="relative z-10 px-6 sm:px-10 lg:px-16 py-14 lg:py-20 flex flex-col justify-center grain-overlay">
            <div class="flex items-center gap-3 mb-5">
                <span class="inline-block h-[3px] w-10 bg-flag-500"></span>
                <span class="meta-stamp">Serikat Pekerja Tani Karawang · Est. 3 Nov 2007 · Kongres I</span>
            </div>

            <h1 class="font-display leading-[0.86] uppercase tracking-[0.01em] text-ink-900">
                <span class="block text-[clamp(3.2rem,7vw,6.25rem)]">PEKERJA</span>
                <span class="block text-[clamp(3.2rem,7vw,6.25rem)]">TANI</span>
                <span class="block text-[clamp(3.2rem,7vw,6.25rem)] text-flag-600">SOKO GURU</span>
                <span class="block text-[clamp(3.2rem,7vw,6.25rem)]">PEMBEBASAN</span>
            </h1>

            <p class="mt-6 max-w-xl text-base sm:text-lg leading-relaxed text-ink-700">
                <?php echo e(\App\Models\SiteSetting::getValue('site_description', 'SEPETAK memperjuangkan reforma agraria sejati — tanah, air, dan benih untuk pekerja tani Karawang. Dari sawah Cikampek hingga pesisir Cilamaya.')); ?>

            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <?php if (isset($component)) { $__componentOriginalb249430bb893250886e66a0e6eefde94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb249430bb893250886e66a0e6eefde94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.btn','data' => ['href' => route('member-registration.create'),'variant' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.btn'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('member-registration.create')),'variant' => 'red']); ?>
                    <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'signature','size' => '18']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'signature','size' => '18']); ?>
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
                    Daftar Anggota
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
                    <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'megaphone','size' => '18']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'megaphone','size' => '18']); ?>
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
                    Baca Berita
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

            
            <div class="mt-10 flex items-center gap-4 text-ink-900">
                <div class="flex flex-col">
                    <span class="font-mono text-[0.7rem] uppercase tracking-widest">Solidaritas</span>
                    <span class="font-mono text-[0.7rem] uppercase tracking-widest">Tanpa Syarat</span>
                </div>
                <div class="w-[3px] h-10 bg-ink-900"></div>
                <div class="flex flex-col">
                    <span class="font-mono text-[0.7rem] uppercase tracking-widest">Tanah Untuk</span>
                    <span class="font-mono text-[0.7rem] uppercase tracking-widest">Penggarapnya</span>
                </div>
            </div>
        </div>

        
        <div class="relative bg-paper-100 border-t-4 border-ink-900 lg:border-t-0 lg:border-l-4 overflow-hidden">
            <?php if (isset($component)) { $__componentOriginal2422a06f87ed39ffa8e0f4dced3ebbbb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2422a06f87ed39ffa8e0f4dced3ebbbb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.landscape-hero','data' => ['class' => 'absolute inset-0 w-full h-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.landscape-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute inset-0 w-full h-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2422a06f87ed39ffa8e0f4dced3ebbbb)): ?>
<?php $attributes = $__attributesOriginal2422a06f87ed39ffa8e0f4dced3ebbbb; ?>
<?php unset($__attributesOriginal2422a06f87ed39ffa8e0f4dced3ebbbb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2422a06f87ed39ffa8e0f4dced3ebbbb)): ?>
<?php $component = $__componentOriginal2422a06f87ed39ffa8e0f4dced3ebbbb; ?>
<?php unset($__componentOriginal2422a06f87ed39ffa8e0f4dced3ebbbb); ?>
<?php endif; ?>
            
            <div class="absolute top-4 right-4 z-10 bg-ink-900 text-paper-50 px-3 py-1">
                <span class="font-mono text-[0.65rem] uppercase tracking-widest">Edisi №1 · 2026</span>
            </div>
        </div>
    </div>
</section>


<?php if (isset($component)) { $__componentOriginale95b6775d589010010157f1de5475bab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale95b6775d589010010157f1de5475bab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.ticker','data' => ['items' => [
        'Tanah Untuk Penggarapnya',
        'Reforma Agraria Sejati',
        'Benih Adalah Hak',
        'Air Adalah Hak',
        'Solidaritas Buruh Tani',
        'Kedaulatan Pangan',
        'Karawang Bangkit',
    ],'tone' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.ticker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        'Tanah Untuk Penggarapnya',
        'Reforma Agraria Sejati',
        'Benih Adalah Hak',
        'Air Adalah Hak',
        'Solidaritas Buruh Tani',
        'Kedaulatan Pangan',
        'Karawang Bangkit',
    ]),'tone' => 'red']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale95b6775d589010010157f1de5475bab)): ?>
<?php $attributes = $__attributesOriginale95b6775d589010010157f1de5475bab; ?>
<?php unset($__attributesOriginale95b6775d589010010157f1de5475bab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale95b6775d589010010157f1de5475bab)): ?>
<?php $component = $__componentOriginale95b6775d589010010157f1de5475bab; ?>
<?php unset($__componentOriginale95b6775d589010010157f1de5475bab); ?>
<?php endif; ?>


<section class="bg-paper-50 py-16 border-b-2 border-ink-900/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (isset($component)) { $__componentOriginal30003712968465b43f1cf960ec6c2621 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30003712968465b43f1cf960ec6c2621 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.section-title','data' => ['eyebrow' => 'Laporan Lapangan','title' => 'Angka yang Kami Perjuangkan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.section-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Laporan Lapangan','title' => 'Angka yang Kami Perjuangkan']); ?>
            Data aktual organisasi per <?php echo e(now()->translatedFormat('F Y')); ?>. Setiap angka adalah wajah anggota, petak sawah, dan surat permohonan yang kami perjuangkan.
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30003712968465b43f1cf960ec6c2621)): ?>
<?php $attributes = $__attributesOriginal30003712968465b43f1cf960ec6c2621; ?>
<?php unset($__attributesOriginal30003712968465b43f1cf960ec6c2621); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30003712968465b43f1cf960ec6c2621)): ?>
<?php $component = $__componentOriginal30003712968465b43f1cf960ec6c2621; ?>
<?php unset($__componentOriginal30003712968465b43f1cf960ec6c2621); ?>
<?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-16">
            <div class="border-t-4 border-ink-900 pt-6">
                <?php if (isset($component)) { $__componentOriginal1e59ad3098c64454bfa1a19797fbf618 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e59ad3098c64454bfa1a19797fbf618 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.stat','data' => ['value' => number_format($stats['member_count']),'label' => 'Anggota Aktif']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['member_count'])),'label' => 'Anggota Aktif']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e59ad3098c64454bfa1a19797fbf618)): ?>
<?php $attributes = $__attributesOriginal1e59ad3098c64454bfa1a19797fbf618; ?>
<?php unset($__attributesOriginal1e59ad3098c64454bfa1a19797fbf618); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e59ad3098c64454bfa1a19797fbf618)): ?>
<?php $component = $__componentOriginal1e59ad3098c64454bfa1a19797fbf618; ?>
<?php unset($__componentOriginal1e59ad3098c64454bfa1a19797fbf618); ?>
<?php endif; ?>
                <p class="mt-3 text-sm text-ink-700 leading-relaxed">Pekerja tani &amp; nelayan terdaftar yang berdiri di garis depan perjuangan.</p>
            </div>
            <div class="border-t-4 border-flag-500 pt-6">
                <?php if (isset($component)) { $__componentOriginal1e59ad3098c64454bfa1a19797fbf618 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e59ad3098c64454bfa1a19797fbf618 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.stat','data' => ['value' => number_format($stats['case_count']),'label' => 'Kasus Agraria']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['case_count'])),'label' => 'Kasus Agraria']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e59ad3098c64454bfa1a19797fbf618)): ?>
<?php $attributes = $__attributesOriginal1e59ad3098c64454bfa1a19797fbf618; ?>
<?php unset($__attributesOriginal1e59ad3098c64454bfa1a19797fbf618); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e59ad3098c64454bfa1a19797fbf618)): ?>
<?php $component = $__componentOriginal1e59ad3098c64454bfa1a19797fbf618; ?>
<?php unset($__componentOriginal1e59ad3098c64454bfa1a19797fbf618); ?>
<?php endif; ?>
                <p class="mt-3 text-sm text-ink-700 leading-relaxed">Sengketa tanah yang sedang didampingi — dari mediasi sampai pengadilan.</p>
            </div>
            <div class="border-t-4 border-ochre-500 pt-6">
                <?php if (isset($component)) { $__componentOriginal1e59ad3098c64454bfa1a19797fbf618 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e59ad3098c64454bfa1a19797fbf618 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.stat','data' => ['value' => number_format($stats['program_count']),'label' => 'Program Advokasi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['program_count'])),'label' => 'Program Advokasi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e59ad3098c64454bfa1a19797fbf618)): ?>
<?php $attributes = $__attributesOriginal1e59ad3098c64454bfa1a19797fbf618; ?>
<?php unset($__attributesOriginal1e59ad3098c64454bfa1a19797fbf618); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e59ad3098c64454bfa1a19797fbf618)): ?>
<?php $component = $__componentOriginal1e59ad3098c64454bfa1a19797fbf618; ?>
<?php unset($__componentOriginal1e59ad3098c64454bfa1a19797fbf618); ?>
<?php endif; ?>
                <p class="mt-3 text-sm text-ink-700 leading-relaxed">Kampanye, pelatihan, dan pengorganisasian yang berjalan aktif.</p>
            </div>
        </div>
    </div>
</section>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestPosts->count()): ?>
<section class="py-20 bg-paper-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
            <?php if (isset($component)) { $__componentOriginal30003712968465b43f1cf960ec6c2621 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30003712968465b43f1cf960ec6c2621 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.section-title','data' => ['eyebrow' => 'Dari Lapangan','title' => 'Kabar Perjuangan Terkini','class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.section-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Dari Lapangan','title' => 'Kabar Perjuangan Terkini','class' => '!mb-0']); ?>
                Ikuti langsung perkembangan organisasi, advokasi kasus, dan aksi-aksi pekerja tani Karawang.
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30003712968465b43f1cf960ec6c2621)): ?>
<?php $attributes = $__attributesOriginal30003712968465b43f1cf960ec6c2621; ?>
<?php unset($__attributesOriginal30003712968465b43f1cf960ec6c2621); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30003712968465b43f1cf960ec6c2621)): ?>
<?php $component = $__componentOriginal30003712968465b43f1cf960ec6c2621; ?>
<?php unset($__componentOriginal30003712968465b43f1cf960ec6c2621); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb249430bb893250886e66a0e6eefde94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb249430bb893250886e66a0e6eefde94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.btn','data' => ['href' => route('posts.index'),'variant' => 'ghost','class' => 'self-start md:self-end flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.btn'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('posts.index')),'variant' => 'ghost','class' => 'self-start md:self-end flex-shrink-0']); ?>
                Semua Berita
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $latestPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $cover = $post->getFirstMediaUrl('cover');
                    $dateLabel = $post->published_at ? $post->published_at->translatedFormat('d M Y') : '—';
                ?>
                <?php if (isset($component)) { $__componentOriginal72831b6bf1f9ea3944be730573665353 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72831b6bf1f9ea3944be730573665353 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.card','data' => ['href' => route('posts.show', $post->slug),'image' => $cover ?: null,'imageAlt' => $post->title,'meta' => $dateLabel,'title' => $post->title,'excerpt' => $post->excerpt]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('posts.show', $post->slug)),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cover ?: null),'image-alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title),'meta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dateLabel),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title),'excerpt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->excerpt)]); ?>
                    <div class="mt-4 flex items-center gap-2 font-display uppercase tracking-widest text-sm text-flag-600">
                        Baca Selengkapnya
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
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72831b6bf1f9ea3944be730573665353)): ?>
<?php $attributes = $__attributesOriginal72831b6bf1f9ea3944be730573665353; ?>
<?php unset($__attributesOriginal72831b6bf1f9ea3944be730573665353); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72831b6bf1f9ea3944be730573665353)): ?>
<?php $component = $__componentOriginal72831b6bf1f9ea3944be730573665353; ?>
<?php unset($__componentOriginal72831b6bf1f9ea3944be730573665353); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<section class="bg-ink-900 text-paper-50 py-20 border-y-4 border-flag-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[5fr_6fr] gap-14 items-start">
            <div>
                <div class="meta-stamp text-flag-500 mb-4 flex items-center gap-3">
                    <span class="inline-block h-[3px] w-8 bg-flag-500"></span>
                    Siapa Kami
                </div>
                <h2 class="font-display text-5xl sm:text-6xl leading-[0.9] uppercase text-paper-50">
                    Berakar di <span class="text-flag-500">Sawah</span>,<br>
                    Berdiri Dengan <span class="text-flag-500">Solidaritas</span>.
                </h2>
                <p class="mt-6 text-paper-100 text-base sm:text-lg leading-relaxed">
                    SEPETAK — <strong class="text-paper-50">Serikat Pekerja Tani Karawang</strong> — adalah organisasi massa pekerja tani dan nelayan yang berdiri sejak 2007 di Kabupaten Karawang, Jawa Barat.
                </p>
                <p class="mt-4 text-paper-200 leading-relaxed">
                    Kami memperjuangkan reforma agraria sejati: redistribusi tanah, perlindungan nelayan pesisir, kedaulatan pangan, dan keadilan sosial untuk buruh tani. Dari Cikampek sampai Cilamaya.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <?php if (isset($component)) { $__componentOriginalb249430bb893250886e66a0e6eefde94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb249430bb893250886e66a0e6eefde94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.btn','data' => ['href' => route('pages.show', 'tentang-kami'),'variant' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.btn'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('pages.show', 'tentang-kami')),'variant' => 'red']); ?>
                        Selengkapnya
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
                    <a href="<?php echo e(route('pages.show', 'visi-misi')); ?>" class="font-display uppercase tracking-widest text-sm text-paper-50 border-b-2 border-flag-500 hover:text-flag-400 pb-1 self-center">
                        Visi &amp; Misi
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    ['icon' => 'scales',    'title' => 'Advokasi Hukum',    'desc' => 'Pendampingan hukum sengketa agraria, dari mediasi sampai PTUN.'],
                    ['icon' => 'wheat',     'title' => 'Pemberdayaan Tani', 'desc' => 'Pelatihan pertanian agroekologi dan koperasi tani.'],
                    ['icon' => 'fist',      'title' => 'Pengorganisasian', 'desc' => 'Membangun basis anggota di ranting-ranting desa Karawang.'],
                    ['icon' => 'megaphone', 'title' => 'Kampanye Publik',  'desc' => 'Kampanye kebijakan pro-tani dan pembelaan petani kriminalisasi.'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative border-2 border-paper-50/40 p-6 bg-ink-800 hover:bg-flag-800 transition-colors group">
                        <div class="absolute -top-2 -left-2 bg-flag-500 text-paper-50 px-2 py-0.5 font-mono text-[0.6rem] uppercase tracking-widest">
                            №<?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?>

                        </div>
                        <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => $item['icon'],'size' => '36','class' => 'text-flag-500 mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'size' => '36','class' => 'text-flag-500 mb-3']); ?>
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
                        <h4 class="font-display text-xl uppercase tracking-wider text-paper-50 mb-2"><?php echo e($item['title']); ?></h4>
                        <p class="text-sm leading-relaxed text-paper-200"><?php echo e($item['desc']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>


<section class="relative bg-flag-500 text-paper-50 py-20 border-b-4 border-ink-900 overflow-hidden">
    
    <div class="absolute inset-0 pointer-events-none opacity-20">
        <svg viewBox="0 0 800 400" class="w-full h-full" preserveAspectRatio="xMidYMid slice">
            <g stroke="#0D0D0D" stroke-width="3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 18; $i++): ?>
                    <line x1="<?php echo e($i * 50); ?>" y1="0" x2="<?php echo e($i * 50 - 300); ?>" y2="400"/>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </g>
        </svg>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="meta-stamp text-paper-50 mb-5 flex items-center justify-center gap-3">
            <span class="inline-block h-[3px] w-10 bg-paper-50"></span>
            <span>Panggilan Solidaritas</span>
            <span class="inline-block h-[3px] w-10 bg-paper-50"></span>
        </div>
        <h2 class="font-display text-5xl sm:text-7xl md:text-8xl leading-[0.88] uppercase tracking-tight">
            Satu Petani<br>
            <span class="inline-block border-b-[10px] border-ink-900 pb-1">Adalah Bayangan.</span><br>
            Seribu Petani<br>
            <span class="inline-block bg-ink-900 text-flag-500 px-4">Adalah Bendera.</span>
        </h2>
        <p class="mt-8 text-lg sm:text-xl text-paper-100 max-w-2xl mx-auto leading-relaxed">
            Bergabunglah dengan SEPETAK. Isi formulir keanggotaan, temui pengurus ranting, dan mulai gerakan dari petak sawah Anda sendiri.
        </p>
        <div class="mt-10 flex flex-wrap justify-center gap-5">
            <a href="<?php echo e(route('member-registration.create')); ?>" class="btn-rev bg-ink-900 text-paper-50 border-paper-50 shadow-[4px_4px_0_#FCF9F1] hover:shadow-[6px_6px_0_#FCF9F1] hover:-translate-x-0.5 hover:-translate-y-0.5 transition">
                <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'signature','size' => '18']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'signature','size' => '18']); ?>
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
                Daftar Menjadi Anggota
            </a>
            <a href="<?php echo e(route('pages.show', 'kontak')); ?>" class="btn-rev bg-transparent text-paper-50 border-paper-50 hover:bg-paper-50 hover:text-flag-500 transition">
                Hubungi Sekretariat
                <?php if (isset($component)) { $__componentOriginal6abde2f60cd7ab2be425b8bf443880bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6abde2f60cd7ab2be425b8bf443880bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rev.icon','data' => ['name' => 'arrow-right','size' => '18']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rev.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','size' => '18']); ?>
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
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sepetak.org/resources/views/home.blade.php ENDPATH**/ ?>