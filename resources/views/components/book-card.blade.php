<div class="group h-full block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-200/80 hover:border-indigo-200 transition-all duration-300 hover:-translate-y-1 flex flex-col overflow-hidden relative">
    {{-- Cover Container --}}
    <div class="relative aspect-[3/4] w-full overflow-hidden bg-slate-100 flex items-center justify-center border-b border-slate-100">
        <a href="{{ route('opac.show', $book) }}" class="absolute inset-0 z-20" title="{{ $book->title }}"></a>
        
        @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
            <img src="{{ asset('storage/' . $book->cover_image) }}" loading="lazy" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" alt="Cover {{ $book->title }}">
        @else
            @php
                $colors = ['from-slate-800 to-indigo-900', 'from-blue-900 to-indigo-950', 'from-indigo-900 to-purple-950', 'from-slate-900 to-sky-950'];
                $gradient = $colors[crc32($book->title) % count($colors)];
                $words = explode(' ', $book->title);
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            @endphp
            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center text-white p-4 text-center relative overflow-hidden transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at center, white 1px, transparent 1px); background-size: 16px 16px;"></div>
                <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-2xl font-black mb-2 shadow-inner border border-white/15 z-10">
                    {{ $initials }}
                </div>
                <span class="text-[10px] opacity-75 font-semibold tracking-widest uppercase z-10 line-clamp-1 px-2">{{ $book->main_author ?? 'SANTO PAULUS' }}</span>
            </div>
        @endif

        {{-- Category Badge --}}
        @php
            $cat = ($book->relationLoaded('subjects') && $book->subjects->isNotEmpty()) 
                    ? $book->subjects->first()->name 
                    : ($book->collection_type ?: 'Umum');
        @endphp
        <div class="absolute top-2.5 right-2.5 z-10">
            <span class="bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm border border-white/80">
                {{ $cat }}
            </span>
        </div>
    </div>

    {{-- Content Details --}}
    <div class="p-3.5 flex flex-col flex-1">
        <div class="flex-1">
            <h3 class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug mb-1">
                <a href="{{ route('opac.show', $book) }}" class="no-underline text-inherit">{{ $book->title }}</a>
            </h3>
            <p class="text-[11px] text-slate-500 line-clamp-1 italic mb-2">
                {{ $book->main_author ?? __('Pengarang tidak terdaftar') }}
            </p>
        </div>

        <div class="border-t border-slate-100 my-2 mt-auto"></div>

        <div class="flex items-center justify-between text-[11px]">
            <span class="text-slate-400 font-medium flex items-center gap-1">
                <i class="bi bi-calendar3 text-[10px]"></i> {{ $book->publication_year ?? '-' }}
            </span>

            @if($book->collection_type === 'E-book')
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60 flex items-center gap-1">
                    <i class="bi bi-file-earmark-pdf"></i> E-Book
                </span>
            @elseif($book->available_items_count > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                    {{ __('Tersedia') }} {{ $book->available_items_count }}
                </span>
            @elseif(isset($book->items_count) && $book->items_count == 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                    {{ __('Stok Kosong') }}
                </span>
            @else
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                    {{ __('Dipinjam') }}
                </span>
            @endif
        </div>
    </div>
</div>
