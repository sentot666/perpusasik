<div class="shadow-sm border-0 hover-card bg-white rounded-xl border border-slate-200 h-full overflow-hidden" style="border-radius:12px;overflow:hidden;transition:transform 0.2s">
    <div style="aspect-ratio:3/4;background:#f8fafc;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;border-bottom:1px solid #e9edf4">
        <a href="{{ route('opac.show', $book) }}" class="absolute inset-0 z-20" title="{{ $book->title }}"></a>
        @if($book->cover_image)
            <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover">
        @else
            @php
                $colors = ['from-blue-500 to-indigo-600', 'from-emerald-400 to-teal-600', 'from-orange-400 to-red-500', 'from-purple-500 to-pink-600', 'from-cyan-500 to-blue-600'];
                $gradient = $colors[crc32($book->title) % count($colors)];
                $words = explode(' ', $book->title);
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            @endphp
            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center text-white p-4 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <span class="text-5xl font-bold opacity-90 mb-3 drop-shadow-md z-10">{{ $initials }}</span>
                <span class="text-xs opacity-75 font-semibold tracking-widest uppercase z-10" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $book->main_author ?? 'MAKARYA' }}</span>
            </div>
        @endif
        <span class="absolute top-0 right-0 m-2 inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 font-semibold px-2" style="font-size:0.68rem">{{ $book->collection_type }}</span>
    </div>
    <div class="flex-col p-8 flex p-6">
        <h6 class="fw-700 text-truncate-2 text-slate-800 mb-1" style="height:38px;line-height:1.2">
            <a href="{{ route('opac.show', $book) }}" class="no-underline text-slate-800">{{ $book->title }}</a>
        </h6>
        <div class="truncate text-slate-500 mb-2" style="font-size:0.75rem">{{ $book->main_author ?? __('Pengarang tidak terdaftar') }}</div>
        <div class="mt-auto border-t border-slate-200 pt-2 justify-between items-center flex">
            <span style="font-size:0.7rem" class="text-slate-500"><i class="bi bi-calendar3 mr-1"></i>{{ $book->publication_year ?? '-' }}</span>
            @if($book->available_items_count > 0)
            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-success-subtle border border-slate-200 border-success-subtle text-emerald-600" style="font-size:0.68rem">{{ __('Tersedia') }} {{ $book->available_items_count }}</span>
            @elseif(isset($book->items_count) && $book->items_count == 0)
            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-secondary-subtle border border-slate-200 text-slate-500" style="font-size:0.68rem">{{ __('Stok Kosong') }}</span>
            @else
            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-danger-subtle border border-slate-200 border-danger-subtle text-red-600" style="font-size:0.68rem">{{ __('Dipinjam') }}</span>
            @endif
        </div>
    </div>
</div>
