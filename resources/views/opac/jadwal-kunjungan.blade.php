@extends('layouts.opac')

@section('title', 'Jadwal Kunjungan Kelas ' . $levelName)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    
    <div class="mb-8">
        <a href="{{ route('opac.agenda') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium text-base transition-colors no-underline">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke Agenda
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-indigo-50 border-b border-indigo-100 p-8 sm:p-12 text-center relative overflow-hidden">
            
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white mb-6 shadow-sm border border-indigo-100 text-4xl text-indigo-600">
                    @if($level == 'sd')
                        <i class="bi bi-backpack text-red-500"></i>
                    @elseif($level == 'smp')
                        <i class="bi bi-book-half text-blue-600"></i>
                    @else
                        <i class="bi bi-mortarboard text-slate-600"></i>
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-3 text-slate-800">Jadwal Kunjungan {{ $levelName }}</h1>
                <p class="text-slate-600 max-w-2xl mx-auto">Jadwal rutin kelas untuk memanfaatkan fasilitas perpustakaan, membaca buku, dan kegiatan literasi wajib mingguan.</p>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-8 sm:p-12">
            
            {{-- Placeholder Info / Dummy Table --}}
            <div class="max-w-4xl mx-auto">
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-xl mb-8 flex items-start gap-4">
                    <i class="bi bi-info-circle-fill text-indigo-500 text-xl mt-0.5"></i>
                    <div>
                        <h4 class="font-bold text-slate-800 text-base mb-1">Informasi Kunjungan</h4>
                        <p class="text-sm text-slate-600 leading-relaxed">Siswa diwajibkan membawa kartu anggota perpustakaan saat jadwal kunjungan kelas. Waktu kunjungan adalah 45 menit (1 jam pelajaran). Silakan ikuti arahan dari wali kelas dan petugas perpustakaan.</p>
                    </div>
                </div>

                {{-- Table Schedule --}}
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-base text-left">
                        <thead class="bg-slate-50 text-slate-700 font-bold uppercase text-sm">
                            <tr>
                                <th scope="col" class="px-6 py-4 border-b border-slate-200">Hari</th>
                                <th scope="col" class="px-6 py-4 border-b border-slate-200">Tanggal</th>
                                <th scope="col" class="px-6 py-4 border-b border-slate-200">Jam (WIB)</th>
                                <th scope="col" class="px-6 py-4 border-b border-slate-200">Kelas / Rombel</th>
                                <th scope="col" class="px-6 py-4 border-b border-slate-200">Guru Pendamping</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse($schedules as $schedule)
                            <tr class="hover:bg-slate-50 transition-colors {{ $loop->even ? 'bg-slate-50/30' : '' }}">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $schedule->day }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $schedule->date ? $schedule->date->format('d M Y') : 'Rutin Mingguan' }}
                                </td>
                                <td class="px-6 py-4">{{ $schedule->time }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-sm font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $schedule->class_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $schedule->teacher_name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    Belum ada jadwal kunjungan kelas untuk tingkat ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
