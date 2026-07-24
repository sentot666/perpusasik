@extends('layouts.app')

@section('title', 'Pengaturan Perpustakaan')
@section('page-title', 'Pengaturan')

@section('breadcrumb')
<li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Pengaturan Perpustakaan</h1>
    <p>Konfigurasi aturan peminjaman, denda, dan data keanggotaan</p>
</div>

<div class="flex flex-wrap -mx-3">
    <div class="w-full lg:w-2/3 px-4">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            @foreach($settings as $group => $items)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="capitalize px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4">
                    <i class="bi bi-gear-wide-connected text-indigo-600 mr-2"></i>
                    @if($group == 'general') Umum @elseif($group == 'circulation') Sirkulasi / Peminjaman @else Keanggotaan @endif
                </div>
                <div class="p-8">
                    @foreach($items as $item)
                    <div class="mb-6 flex flex-wrap -mx-3">
                        <label class="col-sm-4 col-form-label font-medium">{{ $item->label }}</label>
                        <div class="col-sm-8">
                            @if($item->type == 'number')
                                <input type="number" name="settings[{{ $item->key }}]" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $item->value }}" min="0">
                            @elseif($item->type == 'boolean')
                                <div class="form-switch flex items-center gap-2 mt-2">
                                    <input type="hidden" name="settings[{{ $item->key }}]" value="0">
                                    <input class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" type="checkbox" name="settings[{{ $item->key }}]" value="1" {{ $item->value ? 'checked' : '' }}>
                                </div>
                            @else
                                <input type="text" name="settings[{{ $item->key }}]" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $item->value }}">
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="justify-end p-8 flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold gap-2 py-2 px-6">Simpan Pengaturan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
