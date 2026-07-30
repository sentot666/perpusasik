@extends('layouts.app')

@section('title', 'Pengaturan Perpustakaan')
@section('page-title', 'Pengaturan')

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Pengaturan') }}</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Pengaturan Perpustakaan') }}</h1>
    <p>{{ __('Konfigurasi aturan peminjaman, denda, dan data keanggotaan') }}</p>
</div>

<div class="flex flex-wrap -mx-3">
    <div class="w-full lg:w-2/3 px-4">
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-sm">
            <h4 class="font-bold mb-2 flex items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> Terdapat Kesalahan:</h4>
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @foreach($settings as $group => $items)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="capitalize px-8 border-b border-slate-200 bg-slate-50 font-semibold text-slate-800 py-5">
                    <i class="bi bi-gear-wide-connected text-indigo-600 mr-2"></i>
                    @if($group == 'general') {{ __('Umum') }} @elseif($group == 'circulation') {{ __('Sirkulasi / Peminjaman') }} @else {{ __('Keanggotaan') }} @endif
                </div>
                <div class="p-0">
                    <div class="divide-y divide-slate-100">
                        @foreach($items as $item)
                        <div class="p-6 sm:px-8 flex flex-col md:flex-row md:items-center gap-4 hover:bg-slate-50/50 transition-colors">
                            <label class="w-full md:w-1/3 font-medium text-slate-700 text-sm md:text-base">{{ $item->label }}</label>
                            <div class="w-full md:w-2/3 max-w-md">
                                @if($item->type == 'number')
                                    <div class="relative">
                                        <input type="number" name="settings[{{ $item->key }}]" class="w-full rounded-lg border border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none py-2.5 px-4 bg-white transition-all shadow-sm" value="{{ $item->value }}" min="0">
                                    </div>
                                @elseif($item->type == 'boolean')
                                    <div class="form-switch flex items-center gap-3 mt-1">
                                        <input type="hidden" name="settings[{{ $item->key }}]" value="0">
                                        <input class="w-6 h-6 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer shadow-sm transition-all" type="checkbox" name="settings[{{ $item->key }}]" value="1" {{ $item->value ? 'checked' : '' }}>
                                        <span class="text-sm text-slate-500">{{ $item->value ? __('Aktif') : __('Nonaktif') }}</span>
                                    </div>
                                @else
                                    <input type="text" name="settings[{{ $item->key }}]" class="w-full rounded-lg border border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none py-2.5 px-4 bg-white transition-all shadow-sm" value="{{ $item->value }}">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pengaturan Carousel -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="capitalize px-8 border-b border-slate-200 bg-slate-50 font-semibold text-slate-800 py-5">
                    <i class="bi bi-images text-indigo-600 mr-2"></i>
                    {{ __('Tampilan Depan (Carousel)') }}
                </div>
                <div class="p-0">
                    <div class="divide-y divide-slate-100">
                        @for ($i = 1; $i <= 3; $i++)
                        <div class="p-6 sm:px-8 flex flex-col lg:flex-row lg:items-start gap-4 lg:gap-8 hover:bg-slate-50/50 transition-colors">
                            <label class="w-full lg:w-1/3 font-medium text-slate-700 text-sm md:text-base pt-2">{{ __('Gambar Carousel') }} {{ $i }}</label>
                            <div class="w-full lg:w-2/3 flex flex-col sm:flex-row gap-6 items-start">
                                <div class="w-full sm:w-48 shrink-0">
                                    <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden border border-slate-200 shadow-sm relative group">
                                        <img src="{{ asset('images/carousel-'.$i.'.jpg') }}?v={{ time() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Carousel {{ $i }}">
                                        <div class="absolute inset-0 ring-1 ring-inset ring-black/10 rounded-lg"></div>
                                    </div>
                                </div>
                                <div class="flex-1 w-full max-w-md">
                                    <label class="block mb-2">
                                        <span class="sr-only">Choose image</span>
                                        <input type="file" name="carousel_{{ $i }}" class="block w-full text-sm text-slate-500
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100 transition-colors cursor-pointer border border-slate-200 rounded-lg" accept="image/jpeg,image/png,image/webp">
                                    </label>
                                    <p class="text-xs text-slate-500 leading-relaxed mt-2"><i class="bi bi-info-circle mr-1"></i>{{ __('Kosongkan jika tidak ingin mengubah. Format: JPG/PNG. Maks: 2MB.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="justify-end p-6 flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-bold rounded-xl btn-gradient-dark transition-all gap-2 py-3 px-8 shadow-md">
                        <i class="bi bi-save2"></i> {{ __('Simpan Pengaturan') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


