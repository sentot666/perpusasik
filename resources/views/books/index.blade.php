@extends('layouts.app')

@section('title', 'Master Buku')
@section('page-title', 'Master Buku')

@section('breadcrumb')
<li class="breadcrumb-item active">Master Buku</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Master Buku') }}</h1>
        <p>{{ __('Kelola koleksi katalog bibliografi perpustakaan') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('books.create') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-6" id="addBookBtn">
            <i class="bi bi-plus-circle mr-1"></i>{{ __('Tambah Buku') }}
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2" id="filterForm">
            <div class="col-md-5">
                <div class="flex w-full text-sm">
                    <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Cari judul, pengarang, ISBN, no. panggil...') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="w-full md:w-1/4 px-4">
                <select name="collection_type" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                    <option value="">{{ __('Semua Jenis') }}</option>
                    @foreach($collectionTypes as $type)
                    <option value="{{ $type }}" {{ request('collection_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg btn-gradient-blue transition-colors text-white px-4"><i class="bi bi-funnel"></i> {{ __('Filter') }}</button>
                <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
            </div>
            <div class="ml-auto text-slate-500 w-auto px-4" style="font-size:0.8rem">
                {{ $books->total() }} {{ __('judul ditemukan') }}
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
                                <a href="{{ route('books.show', $book) }}" class="no-underline text-slate-800">
                                    {{ Str::limit($book->title, 60) }}
                                </a>
                            </div>
                            @if($book->isbn)
                            <div class="text-slate-500" style="font-size:0.72rem">ISBN: {{ $book->isbn }}</div>
                            @endif
                        </td>
                        <td>{{ $book->main_author ?? '-' }}</td>
                        <td>{{ $book->publisher?->name ?? '-' }}</td>
                        <td>{{ $book->publication_year ?? '-' }}</td>
                        <td><code style="font-size:0.75rem">{{ $book->call_number ?? '-' }}</code></td>
                        <td><span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 text-slate-800">{{ $book->collection_type }}</span></td>
                        <td class="text-center font-semibold">{{ $book->items_count }}</td>
                        <td class="text-center">
                            @if($book->available_items_count > 0)
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ $book->available_items_count }}</span>
                            @else
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-700 px-2">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="inline-flex rounded-md shadow-sm rounded-lg">
                                <a href="{{ route('books.show', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors gap-2 py-2 px-6" title="{{ __('Detail') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('{{ __('Hapus buku ini?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-red-600 border border-slate-200 border-red-600 hover:bg-red-50 transition-colors gap-2 py-2 px-6" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-slate-500">
                            <i class="bi bi-journals text-4xl font-bold block opacity-25 mb-2"></i>
                            {{ __('Belum ada data buku.') }} <a href="{{ route('books.create') }}">{{ __('Tambah buku pertama') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($books->hasPages())
    <div class="bg-white border-t border-slate-200 px-8 bg-slate-50 py-2 py-4">
        {{ $books->links() }}
    </div>
    @endif
</div>
@endsection


