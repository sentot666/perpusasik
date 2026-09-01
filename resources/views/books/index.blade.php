@extends('layouts.app')

@section('title', 'Master Buku')
@section('page-title', 'Master Buku')

@section('breadcrumb')
<li class="breadcrumb-item active">Master Buku</li>
@endsection

@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Master Buku') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Kelola koleksi katalog bibliografi perpustakaan') }}</p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('books.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-5" id="addBookBtn">
            <i class="bi bi-plus-circle"></i>{{ __('Tambah Buku') }}
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-visible mb-6">
    <div class="p-4 sm:p-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center" id="filterForm">
            <div class="lg:col-span-6">
                <div class="flex w-full text-sm relative" x-data="autocompleteSearch('{{ addslashes(request('search')) }}')" @click.away="isOpen = false">
                    <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600 rounded-l-lg"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="w-full rounded-r-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-3" placeholder="{{ __('Cari judul, pengarang, ISBN, no. panggil...') }}" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                    
                    <!-- Autocomplete Dropdown -->
                    <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden z-[100]" style="display: none;">
                        <template x-for="item in suggestions" :key="item.text + item.type">
                            <button type="button" @click="selectSuggestion(item.text)" class="w-full text-left px-4 py-2 hover:bg-slate-50 border-b border-slate-50 last:border-0 flex items-center justify-between group transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                        <i :class="'bi ' + item.icon"></i>
                                    </div>
                                    <span class="text-slate-700 font-medium text-sm truncate" x-text="item.text"></span>
                                </div>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 group-hover:text-indigo-400 flex-shrink-0 ml-2" x-text="item.type"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-3">
                <select name="collection_type" class="w-full rounded-lg border border-slate-300 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-3" onchange="this.form.submit()">
                    <option value="">{{ __('Semua Jenis') }}</option>
                    @foreach($collectionTypes as $type)
                    <option value="{{ $type }}" {{ request('collection_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3 flex items-center justify-between gap-2">
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-2 text-xs font-semibold rounded-lg btn-gradient-blue transition-colors text-white px-3"><i class="bi bi-funnel"></i> {{ __('Filter') }}</button>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center gap-1.5 py-2 text-xs font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors px-3"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
                </div>
                <div class="text-slate-500 text-xs font-medium whitespace-nowrap">
                    {{ $books->total() }} {{ __('judul') }}
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Judul') }}</th>
                        <th>{{ __('Pengarang') }}</th>
                        <th>{{ __('Penerbit') }}</th>
                        <th>{{ __('Tahun') }}</th>
                        <th>{{ __('No. Panggil') }}</th>
                        <th>{{ __('Jenis') }}</th>
                        <th class="text-center">{{ __('Eks.') }}</th>
                        <th class="text-center">{{ __('Tersedia') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="text-slate-500">{{ $books->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-medium">
                                <a href="{{ route('books.show', $book) }}" class="no-underline text-slate-800 hover:text-indigo-600">
                                    {{ Str::limit($book->title, 60) }}
                                </a>
                            </div>
                            @if($book->isbn)
                            <div class="text-slate-500 text-xs">ISBN: {{ $book->isbn }}</div>
                            @endif
                        </td>
                        <td>{{ $book->main_author ?? '-' }}</td>
                        <td>{{ $book->publisher?->name ?? '-' }}</td>
                        <td>{{ $book->publication_year ?? '-' }}</td>
                        <td><code class="text-xs bg-slate-100 px-1.5 py-0.5 rounded">{{ $book->call_number ?? '-' }}</code></td>
                        <td><span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 text-slate-800">{{ $book->collection_type }}</span></td>
                        <td class="text-center font-semibold">{{ $book->items_count }}</td>
                        <td class="text-center">
                            @if($book->available_items_count > 0)
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ $book->available_items_count }}</span>
                            @else
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-slate-100 text-slate-700 px-2">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('books.show', $book) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Detail') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('edit-books')
                                <a href="{{ route('books.edit', $book) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('delete-books')
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-slate-500">
                            <i class="bi bi-journals text-3xl block mb-2 text-slate-300"></i>
                            {{ __('Tidak ada data buku ditemukan') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($books->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function autocompleteSearch(initialQuery = '') {
        return {
            query: initialQuery,
            suggestions: [],
            isOpen: false,
            fetchSuggestions() {
                if (this.query.length < 2) {
                    this.suggestions = [];
                    this.isOpen = false;
                    return;
                }
                
                fetch(`/books/autocomplete?q=${encodeURIComponent(this.query)}`)
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data;
                        this.isOpen = data.length > 0;
                    })
                    .catch(err => {
                        console.error('Error fetching autocomplete:', err);
                    });
            },
            selectSuggestion(text) {
                this.query = text;
                this.isOpen = false;
                
                this.$nextTick(() => {
                    document.getElementById('filterForm').submit();
                });
            }
        }
    }
</script>
@endpush

@endsection
