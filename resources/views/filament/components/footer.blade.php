{{-- Footer panel admin — stempel identitas + tautan situs publik. --}}
<footer class="mt-8 border-t-2 border-ink-900/20 dark:border-ink-200/25">
    <div class="px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3 text-[0.7rem] font-mono uppercase tracking-widest text-ink-700">
            <span class="inline-block h-[3px] w-8 bg-flag-500"></span>
            <span>Serikat Pekerja Tani Karawang · Sejak 2007</span>
        </div>
        <div class="flex items-center gap-4 text-[0.7rem] font-mono uppercase tracking-widest">
            <a href="{{ url('/') }}" target="_blank" rel="noopener" class="text-ink-700 hover:text-flag-600 dark:hover:text-flag-300 hover:underline">
                Situs Publik
            </a>
            <span aria-hidden="true" class="opacity-40">|</span>
            <a href="{{ url('/feed.xml') }}" target="_blank" rel="noopener" class="text-ink-700 hover:text-flag-600 dark:hover:text-flag-300 hover:underline">
                RSS
            </a>
        </div>
    </div>
</footer>
