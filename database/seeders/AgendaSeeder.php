<?php

namespace Database\Seeders;

use App\Models\Agenda;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        $agendas = [
            [
                'title'           => 'Workshop Literasi Digital & Pengenalan OPAC',
                'category'        => 'Workshop',
                'description'     => 'Pelatihan literasi digital bagi siswa-siswi dan guru untuk memanfaatkan layanan sistem pencarian katalog online perpustakaan secara cepat dan efisien.',
                'event_date'      => now()->addDays(2)->format('Y-m-d'),
                'start_time'      => '09:00:00',
                'end_time'        => '11:30:00',
                'location'        => 'Ruang Multimedia Lt. 2',
                'speaker'         => 'Drs. Antonius Wijaya, M.Hum',
                'target_audience' => 'Siswa & Guru',
                'quota'           => 50,
                'status'          => 'Akan Datang',
                'is_published'    => true,
            ],
            [
                'title'           => 'Bedah Buku: Jejak Santo Paulus & Sejarah Pendidikan',
                'category'        => 'Bedah Buku',
                'description'     => 'Diskusi bedah karya literatur sejarah pendidikan serta penelusuran jejak pelayanan Santo Paulus bersama narasumber penulis dan sejarawan.',
                'event_date'      => now()->addDays(7)->format('Y-m-d'),
                'start_time'      => '13:00:00',
                'end_time'        => '15:30:00',
                'location'        => 'Aula Utama Santo Paulus',
                'speaker'         => 'Dr. Maria Francisca, S.S., M.Pd',
                'target_audience' => 'Umum & Anggota',
                'quota'           => 100,
                'status'          => 'Akan Datang',
                'is_published'    => true,
            ],
            [
                'title'           => 'Lomba Menulis Resensi Buku Tingkat Sekolah',
                'category'        => 'Lomba',
                'description'     => 'Ajang kompetisi menulis resensi buku pilihan dari perpustakaan untuk meningkatkan minat baca dan daya kritis menulis generasi muda.',
                'event_date'      => now()->addDays(14)->format('Y-m-d'),
                'start_time'      => '08:30:00',
                'end_time'        => '12:00:00',
                'location'        => 'Ruang Diskusi Kelompok',
                'speaker'         => 'Tim Pustakawan Santo Paulus',
                'target_audience' => 'Siswa/i Aktif',
                'quota'           => 40,
                'status'          => 'Akan Datang',
                'is_published'    => true,
            ],
            [
                'title'           => 'Pameran Buku Baru & Peluncuran Pojok Baca Santai',
                'category'        => 'Pameran',
                'description'     => 'Pameran koleksi buku fiksi dan non-fiksi terbaru serta peresmian fasilitas pojok baca santai dengan konsep modern.',
                'event_date'      => now()->subDays(3)->format('Y-m-d'),
                'start_time'      => '09:00:00',
                'end_time'        => '14:00:00',
                'location'        => 'Lobi Utama Perpustakaan',
                'speaker'         => 'Kepala Perpustakaan',
                'target_audience' => 'Umum',
                'quota'           => 150,
                'status'          => 'Selesai',
                'is_published'    => true,
            ],
        ];

        foreach ($agendas as $data) {
            Agenda::create($data);
        }
    }
}
