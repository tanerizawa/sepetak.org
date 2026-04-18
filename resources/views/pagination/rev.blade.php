@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="font-mono text-[0.65rem] uppercase tracking-widest text-ink-700">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900/15 bg-paper-100 text-ink-700 font-mono text-[0.65rem] uppercase tracking-widest">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900 bg-paper-50 text-ink-900 font-mono text-[0.65rem] uppercase tracking-widest hover:bg-paper-100">
                    Sebelumnya
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900/15 bg-paper-50 text-ink-700 font-mono text-[0.65rem] uppercase tracking-widest">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center min-w-10 px-3 py-2 border-2 border-ink-900 bg-ink-900 text-paper-50 font-mono text-[0.65rem] uppercase tracking-widest">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-10 px-3 py-2 border-2 border-ink-900 bg-paper-50 text-ink-900 font-mono text-[0.65rem] uppercase tracking-widest hover:bg-paper-100">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900 bg-paper-50 text-ink-900 font-mono text-[0.65rem] uppercase tracking-widest hover:bg-paper-100">
                    Berikutnya
                </a>
            @else
                <span class="inline-flex items-center justify-center px-3 py-2 border-2 border-ink-900/15 bg-paper-100 text-ink-700 font-mono text-[0.65rem] uppercase tracking-widest">
                    Berikutnya
                </span>
            @endif
        </div>
    </nav>
@endif
