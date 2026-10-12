<div class="group h-full block bg-white rounded-3xl shadow-sm hover:shadow-xl border border-[#dee3e9] hover:border-[#0064e0]/40 transition-all duration-300 hover:-translate-y-1 flex flex-col overflow-hidden relative">
    {{-- Cover Container --}}
    <div class="relative aspect-[3/4] w-full overflow-hidden bg-[#f1f4f7] flex items-center justify-center border-b border-[#dee3e9]">
        <a href="{{ route('opac.show', $book) }}" class="absolute inset-0 z-20" title="{{ $book->title }}"></a>
        
        @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
            <img src="{{ asset('storage/' . $book->cover_image) }}" loading="lazy" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" alt="Cover {{ $book->title }}">
        @else
            @php
                $colors = ['from-[#0a1317] to-[#1c1e21]', 'from-[#0457cb] to-[#0a1317]', 'from-[#1c1e21] to-[#444950]'];
                $gradient = $colors[crc32($book->title) % count($colors)];
                $words = explode(' ', $book->title);
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            @endphp
            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center text-white p-4 text-center relative overflow-hidden transition-transform duration-500 group-hover:scale-105">
                <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-xl font-black mb-2 border border-white/20 z-10">
                    {{ $initials }}
                </div>
                <span class="text-[10px] opacity-75 font-bold tracking-widest uppercase z-10 line-clamp-1 px-2">{{ $book->main_author ?? 'PERPUSTAKAAN' }}</span>
            </div>
        @endif

        {{-- Category Badge (Meta Pill) --}}
        @php
            $cat = ($book->relationLoaded('subjects') && $book->subjects->isNotEmpty()) 
                    ? $book->subjects->first()->name 
                    : ($book->collection_type ?: 'Umum');
        @endphp
        <div class="absolute top-2.5 right-2.5 z-10">
            <span class="bg-black/75 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-sm">
                {{ $cat }}
            </span>
        </div>
    </div>

    {{-- Content Details --}}
    <div class="p-4 flex flex-col flex-1">
        <div class="flex-1">
            <h3 class="text-xs sm:text-sm font-bold text-[#0a1317] group-hover:text-[#0064e0] transition-colors line-clamp-2 leading-snug mb-1">
                <a href="{{ route('opac.show', $book) }}" class="no-underline text-inherit">{{ $book->title }}</a>
            </h3>
            <p class="text-[11px] text-[#5d6c7b] line-clamp-1 mb-2 font-medium">
                {{ $book->main_author ?? __('Pengarang tidak terdaftar') }}
            </p>
        </div>

        <div class="border-t border-[#dee3e9] my-2 mt-auto"></div>

        <div class="flex items-center justify-between text-[11px]">
            <span class="text-[#8595a4] font-medium flex items-center gap-1">
                <i class="bi bi-calendar3 text-[10px]"></i> {{ $book->publication_year ?? '-' }}
            </span>

            @if($book->collection_type === 'E-book')
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#0064e0]/10 text-[#0064e0] border border-[#0064e0]/20 flex items-center gap-1">
                    <i class="bi bi-file-earmark-pdf"></i> E-Book
                </span>
            @elseif($book->available_items_count > 0)
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#31a24c]/10 text-[#31a24c] border border-[#31a24c]/20">
                    {{ __('Tersedia') }} {{ $book->available_items_count }}
                </span>
            @elseif(isset($book->items_count) && $book->items_count == 0)
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#f7b928]/15 text-[#8f6a00] border border-[#f7b928]/30">
                    {{ __('Stok Kosong') }}
                </span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#e41e3f]/10 text-[#e41e3f] border border-[#e41e3f]/20">
                    {{ __('Dipinjam') }}
                </span>
            @endif
        </div>
    </div>
</div>
