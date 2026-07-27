@extends('layouts.opac')

@section('title', __('Katalog Buku'))

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-6 mt-8">
    
    {{-- Search Bar --}}
    <div class="mb-8 flex flex-col md:flex-row gap-4 items-center">
        <form action="{{ route('opac.katalog') }}" method="GET" class="w-full flex-1 shadow-sm rounded-xl overflow-hidden flex bg-white border border-slate-200 p-1">
            <span class="flex items-center px-4 bg-white text-slate-400"><i class="bi bi-search text-xl"></i></span>
            <input type="text" name="q" class="w-full border-0 focus:ring-0 text-slate-700 py-3 px-2 text-base outline-none" placeholder="{{ __('Ketik kata kunci judul, pengarang, penerbit, atau ISBN...') }}" value="{{ request('q') }}">
            <button type="submit" class="btn-gradient-blue text-white font-bold py-3 px-8 rounded-lg transition-colors whitespace-nowrap">{{ __('Cari') }}</button>
        </form>
        <a href="{{ route('opac.index') }}" class="w-full md:w-auto bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white shadow-md shadow-blue-500/30 rounded-xl px-6 py-3.5 flex items-center justify-center font-semibold transition-all whitespace-nowrap no-underline group border-0">
            {{ __('Kembali') }} <i class="bi bi-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
        </a>
    </div>

    <div class="flex flex-wrap -mx-4">
        {{-- Left side: Filters --}}
        <div class="w-full lg:w-1/4 px-4">
            <div class="shadow-sm border-0 bg-white rounded-xl border border-slate-200 overflow-hidden mb-6" style="border-radius:12px">
                <div class="bg-white px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 font-bold py-4"><i class="bi bi-funnel mr-2"></i>{{ __('Filter Pencarian') }}</div>
                <div class="p-8">
                    <form action="{{ route('opac.katalog') }}" method="GET" id="opacFilterForm">
                        @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div class="mb-6">
                            <label class="text-slate-500 block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">{{ __('Jenis Koleksi') }}</label>
                            <select name="collection_type" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                                <option value="">{{ __('Semua Jenis') }}</option>
                                @foreach($collectionTypes as $type)
                                <option value="{{ $type }}" {{ request('collection_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="text-slate-500 block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">{{ __('Tahun Terbit') }}</label>
                            <select name="year" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                                <option value="">{{ __('Semua Tahun') }}</option>
                                @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="text-slate-500 block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">{{ __('Bahasa') }}</label>
                            <select name="language" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                                <option value="">{{ __('Semua Bahasa') }}</option>
                                <option value="id" {{ request('language') == 'id' ? 'selected' : '' }}>{{ __('Indonesia') }}</option>
                                <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>{{ __('Inggris') }}</option>
                            </select>
                        </div>

                        <a href="{{ route('opac.katalog') }}" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> {{ __('Bersihkan Filter') }}</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right side: Book grid --}}
        <div class="w-full lg:w-3/4 px-4">
            @if(request('q') || request('collection_type') || request('year') || request('language'))
                <div class="alert alert-light border border-slate-200 shadow-sm justify-between items-center flex mb-6 py-2 px-4" style="border-radius:10px;font-size:0.85rem">
                    <div>
                        {{ __('Menampilkan hasil pencarian untuk:') }}
                        @if(request('q')) <strong>"{{ request('q') }}"</strong> @endif
                        @if(request('collection_type')) <span class="ml-1 inline-flex py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-700 px-2">{{ request('collection_type') }}</span> @endif
                        @if(request('year')) <span class="ml-1 inline-flex py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-700 px-2">{{ request('year') }}</span> @endif
                        @if(request('language')) <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-sky-500 text-white ml-1">{{ request('language') == 'id' ? __('Indonesia') : __('Inggris') }}</span> @endif
                    </div>
                    <span class="text-slate-500">{{ $books->total() }} {{ __('judul ditemukan') }}</span>
                </div>
            @endif

            <div class="flex flex-wrap -mx-3">
                @forelse($books as $book)
                <div class="w-1/2 sm:w-1/3 lg:w-1/4 px-3 mb-6">
                    <div class="shadow-sm border-0 hover-card bg-white rounded-xl border border-slate-200 h-full overflow-hidden" style="border-radius:12px;overflow:hidden;transition:transform 0.2s">
                        <div style="aspect-ratio:3/4;background:#f8fafc;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;border-bottom:1px solid #e9edf4">
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
                                @else
                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-danger-subtle border border-slate-200 border-danger-subtle text-red-600" style="font-size:0.68rem">{{ __('Dipinjam') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-slate-500 w-full px-4">
                    <i class="bi bi-journals text-4xl font-bold block opacity-25 mb-6"></i>
                    {{ __('Buku tidak ditemukan. Coba masukkan kata kunci pencarian yang lain.') }}
                </div>
                @endforelse
            </div>

            @if($books->hasPages())
            <div class="mt-6">
                {{ $books->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08)!important;
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>


@endsection

