@extends('layouts.opac')

@section('title', 'Program Kerja Perpustakaan')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8 mb-16 px-4">
    @if(isset($page) && $page)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12">
            <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                {!! $page->content !!}
            </div>
        </div>
    @else
    {{-- Header & Pendahuluan --}}
    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl p-8 md:p-12 text-white mb-10 shadow-lg relative overflow-hidden">
        <!-- Decorative SVG Background -->
        <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
            <svg width="400" height="400" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L2 22h20L12 2zm0 3.8l7.2 14.2H4.8L12 5.8z"/>
            </svg>
        </div>

        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-4 tracking-tight">Program Kerja Perpustakaan Lintas Jenjang</h1>
            <p class="text-indigo-100 max-w-3xl leading-relaxed text-lg mb-6">
                Membangun budaya membaca yang konsisten bagi seluruh warga sekolah dari jenjang SD hingga SMA. Program kerja ini dirancang dengan prinsip <strong>Simplicite, Regularite, Travail</strong>.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
                <span class="inline-flex items-center gap-1.5 bg-white/20 px-3 py-1.5 rounded-lg text-sm font-medium backdrop-blur-sm"><i class="bi bi-book"></i> Literasi Anak Usia Dini (SD)</span>
                <span class="inline-flex items-center gap-1.5 bg-white/20 px-3 py-1.5 rounded-lg text-sm font-medium backdrop-blur-sm"><i class="bi bi-journal-text"></i> Interaksi Sosial (SMP)</span>
                <span class="inline-flex items-center gap-1.5 bg-white/20 px-3 py-1.5 rounded-lg text-sm font-medium backdrop-blur-sm"><i class="bi bi-laptop"></i> Riset & Akademik (SMA)</span>
            </div>
        </div>
    </div>

    {{-- 5 Program Utama --}}
    <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
        <i class="bi bi-star-fill text-amber-400 text-xl"></i> 5 Program Unggulan
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-4">
                <i class="bi bi-box-seam"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">1. Garda Buku</h3>
            <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                Program Hibah Lulusan & Alumni (Kelas 6 SD, 9 SMP, 12 SMA). Meremajakan koleksi buku secara rutin tanpa membebankan anggaran sekolah.
            </p>
            <div class="mt-auto border-t border-slate-100 pt-4">
                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                    <i class="bi bi-clock-history"></i> Akhir Semester / Jelang Lulus
                </span>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-2xl mb-4">
                <i class="bi bi-calendar-week"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">2. Optimasi Kunjungan</h3>
            <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                Kunjungan tematik terjadwal. SD dengan <em>Read Aloud</em>, SMP dengan pencarian referensi, dan SMA fokus pada <em>Quiet Reading</em> & bimbingan katalog.
            </p>
            <div class="mt-auto border-t border-slate-100 pt-4">
                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                    <i class="bi bi-calendar2-check"></i> Terjadwal per Kelas
                </span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-2xl mb-4">
                <i class="bi bi-cup-hot"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">3. Pojok Baca Tematik</h3>
            <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                Mendekatkan akses buku ke area interaksi siswa (Kantin, Lorong, Depan Kelas). Sistem bebas baca di tempat dengan rotasi buku tiap minggu.
            </p>
            <div class="mt-auto border-t border-slate-100 pt-4">
                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                    <i class="bi bi-arrow-repeat"></i> Rotasi 1x / Minggu
                </span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-2xl mb-4">
                <i class="bi bi-pin-angle"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">4. Lomba Resensi Kilat</h3>
            <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                Dinding Rekomendasi (*corkboard*) untuk mengekspresikan ulasan singkat menggunakan *post-it*. Ulasan terunik mendapat apresiasi bulanan.
            </p>
            <div class="mt-auto border-t border-slate-100 pt-4">
                <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                    <i class="bi bi-award"></i> Evaluasi Bulanan
                </span>
            </div>
        </div>

        <!-- Card 5 -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center text-2xl mb-4">
                <i class="bi bi-bar-chart-steps"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">5. Peta Literasi Kelas</h3>
            <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                Kompetisi sirkulasi peminjaman buku antarkelas. Kelas tertinggi di akhir semester meraih Piala Bergilir Literasi atau Piagam.
            </p>
            <div class="mt-auto border-t border-slate-100 pt-4">
                <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                    <i class="bi bi-trophy"></i> Penghargaan Semesteran
                </span>
            </div>
        </div>

    </div>

    {{-- Matriks Pelaksanaan Tabel --}}
    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="bi bi-table text-indigo-500"></i> Matriks Pelaksanaan
    </h2>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-12">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap md:whitespace-normal">
                <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-bold">Program Kerja</th>
                        <th class="px-6 py-4 font-bold">Frekuensi / Waktu</th>
                        <th class="px-6 py-4 font-bold">Penanggung Jawab</th>
                        <th class="px-6 py-4 font-bold">Indikator Keberhasilan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">1. Garda Buku</td>
                        <td class="px-6 py-4"><span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-medium">Akhir Semester</span></td>
                        <td class="px-6 py-4">Tim Kesiswaan & Perpus</td>
                        <td class="px-6 py-4 text-emerald-600 font-medium">100% siswa lulusan menyumbang min. 1 buku</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">2. Optimasi Kunjungan</td>
                        <td class="px-6 py-4"><span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-medium">Terjadwal</span></td>
                        <td class="px-6 py-4">Petugas Perpus & Guru</td>
                        <td class="px-6 py-4 text-emerald-600 font-medium">Seluruh siswa membaca teratur</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">3. Pojok Baca Tematik</td>
                        <td class="px-6 py-4"><span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-medium">Setiap Hari (Rotasi 1x/Mgg)</span></td>
                        <td class="px-6 py-4">Petugas Perpustakaan</td>
                        <td class="px-6 py-4 text-emerald-600 font-medium">Buku di area santai aktif dibaca saat istirahat</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">4. Lomba Resensi Kilat</td>
                        <td class="px-6 py-4"><span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-medium">Bulanan</span></td>
                        <td class="px-6 py-4">Petugas Perpustakaan</td>
                        <td class="px-6 py-4 text-emerald-600 font-medium">Minimal 20 resensi tertempel per bulan</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">5. Peta Literasi Kelas</td>
                        <td class="px-6 py-4"><span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-medium">Rekap Bulanan / Award Semester</span></td>
                        <td class="px-6 py-4">Kepala Perpustakaan</td>
                        <td class="px-6 py-4 text-emerald-600 font-medium">Peningkatan peminjaman buku minimal 20%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
