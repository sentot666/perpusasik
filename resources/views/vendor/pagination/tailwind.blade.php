@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between pt-4 mt-4 border-t border-slate-100">
        <div>
            <p class="text-sm text-slate-500">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <span class="font-medium text-slate-700">{{ $paginator->total() }}</span>
                data
            </p>
        </div>

        <div>
            <ul class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li aria-disabled="true" aria-label="Sebelumnya">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed" aria-hidden="true">
                            <i class="bi bi-chevron-left text-xs"></i>
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-indigo-600 transition-colors" aria-label="Sebelumnya">
                            <i class="bi bi-chevron-left text-xs"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li aria-disabled="true">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 cursor-default">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li aria-current="page">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white font-medium shadow-sm shadow-indigo-200 cursor-default">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-medium transition-colors" aria-label="Halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-indigo-600 transition-colors" aria-label="Selanjutnya">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    </li>
                @else
                    <li aria-disabled="true" aria-label="Selanjutnya">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed" aria-hidden="true">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif