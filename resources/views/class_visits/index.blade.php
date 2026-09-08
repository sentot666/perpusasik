@extends('layouts.app')

@section('title', 'Jadwal Kunjungan Kelas')

@section('header')
    <h2 class="font-semibold text-xl text-slate-800 leading-tight">
        {{ __('Jadwal Kunjungan Kelas') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if (session('success'))
            <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-slate-200">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Kelola Jadwal Kunjungan</h3>
                    <a href="{{ route('class-visits.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors">
                        <i class="bi bi-plus-lg mr-1"></i> Tambah Jadwal
                    </a>
                </div>

                {{-- Filter by Level --}}
                <div class="mb-6 flex gap-2">
                    <a href="{{ route('class-visits.index') }}" class="px-4 py-2 rounded text-sm font-bold {{ request('level') == '' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua</a>
                    <a href="{{ route('class-visits.index', ['level' => 'sd']) }}" class="px-4 py-2 rounded text-sm font-bold {{ request('level') == 'sd' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">SD</a>
                    <a href="{{ route('class-visits.index', ['level' => 'smp']) }}" class="px-4 py-2 rounded text-sm font-bold {{ request('level') == 'smp' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">SMP</a>
                    <a href="{{ route('class-visits.index', ['level' => 'sma']) }}" class="px-4 py-2 rounded text-sm font-bold {{ request('level') == 'sma' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">SMA</a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Level</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hari & Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu (WIB)</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kelas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Guru Pendamping</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($visits as $visit)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800 uppercase">
                                        {{ $visit->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <div class="font-bold">{{ $visit->day }}</div>
                                    @if($visit->date)
                                    <div class="text-xs text-slate-500">{{ $visit->date->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $visit->time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $visit->class_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $visit->teacher_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('class-visits.edit', $visit->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('class-visits.destroy', $visit->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    Belum ada jadwal kunjungan kelas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
