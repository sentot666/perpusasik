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

<div class="row g-3">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            @foreach($settings as $group => $items)
            <div class="card mb-3">
                <div class="card-header text-capitalize">
                    <i class="bi bi-gear-wide-connected me-2 text-primary"></i>
                    @if($group == 'general') Umum @elseif($group == 'circulation') Sirkulasi / Peminjaman @else Keanggotaan @endif
                </div>
                <div class="card-body">
                    @foreach($items as $item)
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-500">{{ $item->label }}</label>
                        <div class="col-sm-8">
                            @if($item->type == 'number')
                                <input type="number" name="settings[{{ $item->key }}]" class="form-control" value="{{ $item->value }}" min="0">
                            @elseif($item->type == 'boolean')
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="settings[{{ $item->key }}]" value="0">
                                    <input class="form-check-input" type="checkbox" name="settings[{{ $item->key }}]" value="1" {{ $item->value ? 'checked' : '' }}>
                                </div>
                            @else
                                <input type="text" name="settings[{{ $item->key }}]" class="form-control" value="{{ $item->value }}">
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="card">
                <div class="card-body d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary fw-600">Simpan Pengaturan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
