@if ($paginator->hasPages())
    <nav class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between" role="navigation" aria-label="Pagination Navigation">
        <div class="text-xs font-semibold text-slate-500">
            Menampilkan
            <span class="font-bold text-slate-700">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span class="font-bold text-slate-700">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-bold text-slate-700">{{ $paginator->total() }}</span>
            data
        </div>

        <div class="flex flex-wrap items-center justify-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-400">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">Prev</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-xs font-bold text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex min-w-10 items-center justify-center rounded-xl bg-slate-900 px-3 py-2 text-xs font-extrabold text-white shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex min-w-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">Next</a>
            @else
                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-400">Next</span>
            @endif
        </div>
    </nav>
@endif

