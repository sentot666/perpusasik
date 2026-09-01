@extends('layouts.app')

@section('title', __('Detail Buku') . ': ' . $book->title)
@section('page-title', __('Detail Buku'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Master Buku') }}</a></li>
<li class="breadcrumb-item active">{{ Str::limit($book->title, 30) }}</li>
@endsection

@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Detail Buku') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Detail bibliografi dan eksemplar fisik buku') }}</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
        <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors gap-1.5 py-2 px-4 shadow-sm">
            <i class="bi bi-arrow-left"></i>{{ __('Kembali') }}
        </a>
        <a href="{{ route('books.barcode', $book) }}" target="_blank" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg bg-amber-500 hover:bg-amber-600 transition-colors text-white gap-1.5 py-2 px-4 shadow-sm">
            <i class="bi bi-upc-scan"></i>{{ __('Cetak Barcode') }}
        </a>
        <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors gap-1.5 py-2 px-4 bg-white shadow-sm">
            <i class="bi bi-pencil"></i>{{ __('Edit') }}
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    {{-- Left side: Bibliografi detail --}}
    <div class="lg:col-span-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 text-sm flex items-center gap-2">
                <i class="bi bi-journal-text text-indigo-600"></i>{{ __('Informasi Bibliografi') }}
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/4 flex justify-center">
                        <div class="w-32 aspect-[3/4] bg-slate-100 rounded-lg border border-slate-200 overflow-hidden flex items-center justify-center text-slate-400">
                            @if($book->cover_image && file_exists(public_path('storage/' . $book->cover_image)))
                                <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-contain" alt="{{ $book->title }}">
                            @else
                                <i class="bi bi-book text-4xl"></i>
                            @endif
                        </div>
                    </div>
                    <div class="w-full md:w-3/4">
                        <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $book->title }}</h3>
                        @if($book->subtitle)
                        <h5 class="text-slate-500 text-sm mb-3">{{ $book->subtitle }}</h5>
                        @endif

                        <div class="mb-4 flex flex-wrap gap-2">
                            <span class="inline-flex py-1 text-xs font-semibold rounded-md bg-indigo-100 text-indigo-700 px-2.5">{{ $book->collection_type }}</span>
                            <span class="inline-flex py-1 text-xs font-semibold rounded-md bg-slate-100 border border-slate-200 text-slate-800 px-2.5">{{ $book->language == 'id' ? __('Bahasa Indonesia') : __('Bahasa Asing') }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                                <tbody class="divide-y divide-slate-100">
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold w-32 uppercase text-xs">{{ __('PENGARANG') }}</td>
                                        <td class="py-2">: 
                                            @forelse($book->authors as $author)
                                                <span class="font-medium text-slate-800">{{ $author->name }}</span>{{ !$loop->last ? ', ' : '' }}
                                            @empty
                                                <span class="text-slate-400">-</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold uppercase text-xs">{{ __('PENERBIT') }}</td>
                                        <td class="py-2">: {{ $book->publisher?->name ?? '-' }} @if($book->publisher?->city) ({{ $book->publisher->city }}) @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold uppercase text-xs">{{ __('TAHUN TERBIT') }}</td>
                                        <td class="py-2">: {{ $book->publication_year ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold uppercase text-xs">{{ __('EDISI / SERI') }}</td>
                                        <td class="py-2">: {{ $book->edition ?? '-' }} @if($book->series_title) ({{ __('Seri') }}: {{ $book->series_title }} #{{ $book->series_number }}) @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold uppercase text-xs">{{ __('ISBN') }}</td>
                                        <td class="py-2">: {{ $book->isbn ?? '-' }} @if($book->isbn13) / {{ $book->isbn13 }} @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold uppercase text-xs">{{ __('KLASIFIKASI') }}</td>
                                        <td class="py-2">: {{ __('DDC') }} {{ $book->ddc ?? '-' }} / {{ __('No. Panggil') }}: <code class="bg-slate-100 px-1.5 py-0.5 rounded font-mono text-xs">{{ $book->call_number ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold uppercase text-xs">{{ __('FISIK') }}</td>
                                        <td class="py-2">: {{ $book->pages ? $book->pages . ' hlm' : '-' }}; {{ $book->dimensions ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-500 font-semibold uppercase text-xs">{{ __('SUBYEK') }}</td>
                                        <td class="py-2">: 
                                            @forelse($book->subjects as $sub)
                                                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 mr-1">{{ $sub->name }}</span>
                                            @empty
                                                <span class="text-slate-400">-</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($book->abstract)
                <div class="border-t border-slate-100 mt-6 pt-4 text-xs sm:text-sm">
                    <h6 class="font-bold text-slate-800 mb-2">{{ __('Abstrak / Catatan Ringkas') }}:</h6>
                    <p class="text-slate-600 leading-relaxed">{{ $book->abstract }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right side: Copies summary --}}
    <div class="lg:col-span-4 flex flex-col gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden text-center">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 text-sm flex items-center justify-center gap-2">
                <i class="bi bi-info-circle text-indigo-600"></i>{{ __('Ketersediaan Buku') }}
            </div>
            <div class="p-6">
                <div class="flex items-center justify-around">
                    <div>
                        <div class="text-3xl font-extrabold text-indigo-600">{{ $book->items()->count() }}</div>
                        <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider mt-1">{{ __('Total Eksemplar') }}</div>
                    </div>
                    <div class="w-px h-10 bg-slate-200"></div>
                    <div>
                        <div class="text-3xl font-extrabold text-emerald-600">{{ $book->items()->where('status', 'Tersedia')->count() }}</div>
                        <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider mt-1">{{ __('Tersedia') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 text-sm flex items-center gap-2">
                <i class="bi bi-geo-alt text-red-500"></i>{{ __('Rak Penyimpanan') }}
            </div>
            <div class="p-4 space-y-2">
                @php
                    $itemLocations = $book->items()->with('location')->get()->pluck('location.name')->unique()->filter();
                @endphp
                @forelse($itemLocations as $loc)
                    <div class="px-3 py-2 rounded-lg text-xs font-semibold bg-slate-50 border border-slate-200 text-slate-800 flex items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-red-500"></i>{{ $loc }}
                    </div>
                @empty
                    <div class="text-slate-400 text-xs text-center py-2">{{ __('Belum ada lokasi eksemplar') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Eksemplar Section --}}
<div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="justify-between items-center px-6 py-4 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 text-sm flex flex-col sm:flex-row gap-3">
        <span class="flex items-center gap-2"><i class="bi bi-upc-scan text-indigo-600"></i>{{ __('Daftar Eksemplar Fisik') }}</span>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button type="button" onclick="printSelected()" class="inline-flex items-center justify-center gap-1.5 py-2 text-xs font-semibold rounded-lg bg-emerald-500 hover:bg-emerald-600 transition-colors text-white px-4">
                <i class="bi bi-printer"></i>{{ __('Cetak Terpilih') }}
            </button>
            <a href="{{ route('books.items.create', $book) }}" class="inline-flex items-center justify-center gap-1.5 py-2 text-xs font-semibold rounded-lg btn-gradient-blue transition-colors text-white px-4">
                <i class="bi bi-plus-circle"></i>{{ __('Tambah Eksemplar') }}
            </a>
        </div>
    </div>
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th class="w-10 text-center"><input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"></th>
                        <th>{{ __('Barcode / RFID') }}</th>
                        <th>{{ __('Kopi') }}</th>
                        <th>{{ __('Nomor Induk') }}</th>
                        <th>{{ __('Lokasi / Rak') }}</th>
                        <th>{{ __('Kondisi') }}</th>
                        <th>{{ __('Tanggal Perolehan') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($book->items as $item)
                    <tr>
                        <td class="text-center"><input type="checkbox" name="items[]" value="{{ $item->id }}" class="item-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"></td>
                        <td><code class="font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded text-xs">{{ $item->barcode }}</code></td>
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                c{{ $loop->iteration }}
                            </span>
                        </td>
                        <td>{{ $item->accession_number ?? '-' }}</td>
                        <td>
                            @if($item->location)
                            <span class="font-medium"><i class="bi bi-geo-alt mr-1 text-red-500"></i>{{ $item->location->name }}</span>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->condition === 'Baik')
                            <span class="inline-flex py-0.5 text-xs font-semibold rounded-md bg-emerald-100 text-emerald-800 px-2.5">Baik</span>
                            @elseif($item->condition === 'Rusak')
                            <span class="inline-flex py-0.5 text-xs font-semibold rounded-md bg-red-100 text-red-800 px-2.5">Rusak</span>
                            @else
                            <span class="inline-flex py-0.5 text-xs font-semibold rounded-md bg-amber-100 text-amber-800 px-2.5">{{ $item->condition }}</span>
                            @endif
                        </td>
                        <td>{{ $item->acquisition_date ? $item->acquisition_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($item->status === 'Tersedia')
                            <span class="inline-flex py-0.5 text-xs font-semibold rounded-md bg-emerald-100 text-emerald-800 px-2.5">Tersedia</span>
                            @elseif($item->status === 'Dipinjam')
                            <span class="inline-flex py-0.5 text-xs font-semibold rounded-md bg-amber-100 text-amber-800 px-2.5">Dipinjam</span>
                            @else
                            <span class="inline-flex py-0.5 text-xs font-semibold rounded-md bg-slate-100 text-slate-800 px-2.5">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('book-items.barcode', $item) }}" target="_blank" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="{{ __('Cetak Barcode Eksemplar Ini') }}">
                                    <i class="bi bi-upc-scan"></i>
                                </a>
                                <a href="{{ route('items.edit', $item) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('{{ __('Hapus eksemplar ini?') }}')" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-slate-500 py-8">
                            <i class="bi bi-upc-scan text-3xl block mb-2 text-slate-300"></i>
                            {{ __('Belum ada eksemplar fisik untuk buku ini.') }} 
                            <a href="{{ route('books.items.create', $book) }}" class="text-indigo-600 font-semibold hover:underline block mt-1">{{ __('Tambah eksemplar pertama') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('selectAll').addEventListener('change', function(e) {
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.checked = e.target.checked;
        });
    });

    function printSelected() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) {
            alert('Pilih setidaknya satu eksemplar untuk dicetak.');
            return;
        }
        
        let url = '{{ route("books.barcode", $book) }}?';
        const ids = Array.from(checked).map(cb => 'items[]=' + encodeURIComponent(cb.value)).join('&');
        url += ids;
        
        window.open(url, '_blank');
    }
</script>
@endsection
