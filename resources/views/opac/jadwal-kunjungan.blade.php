@extends('layouts.opac')

@section('title', 'Jadwal Kunjungan Kelas ' . $levelName)

@section('content')
<div class="w-full px-6 sm:px-8 lg:px-12 py-8">
    
    <div class="mb-6">
        <a href="{{ route('opac.agenda') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-[#1c1e21] border border-[#dee3e9] hover:bg-[#dee3e9] text-xs font-bold transition-all shadow-sm no-underline">
            <i class="bi bi-arrow-left"></i> Kembali ke Agenda Kegiatan
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-[#dee3e9] overflow-hidden">
        {{-- Header: Meta Dark Showcase --}}
        <div class="bg-[#0a1317] text-white p-8 sm:p-12 text-center relative overflow-hidden border-b border-[#dee3e9]/20">
            {{-- Soft Ambient Glow --}}
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-[#0064e0]/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-[#0457cb]/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md mb-6 shadow-sm text-4xl text-white">
                    @if($level == 'sd')
                        <i class="bi bi-backpack text-[#ff4b4b]"></i>
                    @elseif($level == 'smp')
                        <i class="bi bi-book-half text-[#0091ff]"></i>
                    @else
                        <i class="bi bi-mortarboard text-white"></i>
                    @endif
                </div>
                <span class="inline-block px-3.5 py-1 rounded-full bg-white/10 text-[#0091ff] text-xs font-bold uppercase tracking-wider mb-2.5 border border-white/10 shadow-sm">
                    Program Kunjungan Perpustakaan
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold mb-3 text-white tracking-tight">Jadwal Kunjungan {{ $levelName }}</h1>
                <p class="text-[#ced0d4] text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
                    Jadwal rutin kelas untuk memanfaatkan fasilitas perpustakaan, membaca buku, dan kegiatan literasi wajib mingguan.
                </p>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-6 sm:p-12">
            <div class="max-w-5xl mx-auto">
                {{-- Info Callout --}}
                <div class="bg-[#0064e0]/5 border border-[#0064e0]/20 p-5 rounded-2xl mb-8 flex items-start gap-4">
                    <div class="w-9 h-9 rounded-full bg-[#0064e0]/10 text-[#0064e0] flex items-center justify-center flex-shrink-0 text-lg">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-[#0a1317] text-sm mb-1">Informasi Kunjungan</h4>
                        <p class="text-xs text-[#5d6c7b] leading-relaxed m-0">
                            Siswa diwajibkan membawa kartu anggota perpustakaan saat jadwal kunjungan kelas. Waktu kunjungan adalah 45 menit (1 jam pelajaran). Silakan ikuti arahan dari wali kelas dan petugas perpustakaan.
                        </p>
                    </div>
                </div>

                {{-- Table Schedule --}}
                <div class="overflow-x-auto rounded-2xl border border-[#dee3e9] shadow-xs">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#f1f4f7] text-[#0a1317] font-bold uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-4 border-b border-[#dee3e9]">Hari</th>
                                <th scope="col" class="px-6 py-4 border-b border-[#dee3e9]">Tanggal</th>
                                <th scope="col" class="px-6 py-4 border-b border-[#dee3e9]">Jam (WIB)</th>
                                <th scope="col" class="px-6 py-4 border-b border-[#dee3e9]">Kelas / Rombel</th>
                                <th scope="col" class="px-6 py-4 border-b border-[#dee3e9]">Guru Pendamping</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#dee3e9] text-[#1c1e21]">
                            @forelse($schedules as $schedule)
                            <tr class="hover:bg-[#f1f4f7]/50 transition-colors {{ $loop->even ? 'bg-[#f1f4f7]/20' : '' }}">
                                <td class="px-6 py-4 font-bold text-[#0a1317]">{{ $schedule->day }}</td>
                                <td class="px-6 py-4 text-[#5d6c7b]">
                                    {{ $schedule->date ? $schedule->date->format('d M Y') : 'Rutin Mingguan' }}
                                </td>
                                <td class="px-6 py-4 font-medium">{{ $schedule->time }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-[#f1f4f7] text-[#0064e0] border border-[#dee3e9]">
                                        {{ $schedule->class_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-[#0a1317]">{{ $schedule->teacher_name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#8595a4]">
                                    <i class="bi bi-calendar-x text-4xl text-[#ced0d4] block mb-2"></i>
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
