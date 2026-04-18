@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between gap-3">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900/15 bg-paper-100 text-ink-700 font-mono text-[0.65rem] uppercase tracking-widest">
                Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900 bg-paper-50 text-ink-900 font-mono text-[0.65rem] uppercase tracking-widest hover:bg-paper-100">
                Sebelumnya
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900 bg-paper-50 text-ink-900 font-mono text-[0.65rem] uppercase tracking-widest hover:bg-paper-100">
                Berikutnya
            </a>
        @else
            <span class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900/15 bg-paper-100 text-ink-700 font-mono text-[0.65rem] uppercase tracking-widest">
                Berikutnya
            </span>
        @endif
    </nav>
@endif
