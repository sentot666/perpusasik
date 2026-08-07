<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ImportStudentsCommand extends Command
{
    protected $signature = 'import:students 
                            {level=SMP : Jenjang sekolah (SD, SMP, SMA)} 
                            {--replace : Hapus anggota lama dengan jenjang yang sama / anggota dummy}';

    protected $description = 'Import data siswa dari hasil ekstrak Excel ke database Member & User portal';

    public function handle(): int
    {
        $level = strtoupper($this->argument('level'));
        $shouldReplace = $this->option('replace');

        $this->info("Memulai import data siswa jenjang: {$level}");

        if ($level === 'SMA') {
            return $this->handleSMA($shouldReplace);
        } elseif ($level === 'SD') {
            return $this->handleSD($shouldReplace);
        } else {
            return $this->handleStandard($level, $shouldReplace);
        }
    }

    protected function handleSD(bool $shouldReplace): int
    {
        $extractedPath = storage_path('app/sd_raw_extracted');
        if (!file_exists($extractedPath . '/xl/worksheets/sheet1.xml')) {
            $this->error("File ekstrak SD tidak ditemukan di: {$extractedPath}");
            return Command::FAILURE;
        }

        $sharedStrings = $this->loadSharedStrings($extractedPath);
        $files = glob($extractedPath . '/xl/worksheets/sheet*.xml');
        sort($files);

        $students = [];

        foreach ($files as $sheetIdx => $sheetFile) {
            $xml = simplexml_load_file($sheetFile);
            $currentClass = "Kelas " . ($sheetIdx + 1);

            foreach ($xml->sheetData->row as $row) {
                $cols = $this->parseRowCells($row, $sharedStrings);

                $a = $cols['A'] ?? '';
                $b = $cols['B'] ?? '';
                $c = $cols['C'] ?? '';
                $d = $cols['D'] ?? '';
                $e = $cols['E'] ?? '';
                $f = $cols['F'] ?? '';
                $g = $cols['G'] ?? '';

                // Header check
                if (stripos($a, 'DAFTAR NAMA') !== false || stripos($a, 'KELAS') !== false) {
                    $cleanedHeader = trim(str_ireplace('DAFTAR NAMA MURID', '', $a));
                    $currentClass = trim($cleanedHeader) ?: ("Kelas " . ($sheetIdx + 1));
                    continue;
                }

                // Student row check
                if (is_numeric($a) && !empty($d)) {
                    $nis = preg_replace('/[^0-9]/', '', $b);
                    $nisn = preg_replace('/[^0-9]/', '', $c);
                    $nama = trim($d);
                    $jk = strtoupper($e) === 'P' ? 'P' : 'L';
                    $telp = trim(str_replace("'", "", $f));
                    $ortu = trim($g);

                    // Estimasi tanggal lahir default berdasarkan jenjang kelas
                    $classNum = $sheetIdx + 1;
                    $estYear = 2020 - $classNum;
                    $defaultBirthDate = "{$estYear}-01-01";

                    $notesArr = [];
                    if (!empty($nisn)) $notesArr[] = "NISN: {$nisn}";
                    if (!empty($ortu)) $notesArr[] = "Ortu: {$ortu}";
                    if (!empty($telp)) $notesArr[] = "Telp: {$telp}";

                    $students[] = [
                        'nama'          => $nama,
                        'nis'           => $nis,
                        'nisn'          => $nisn,
                        'jk'            => $jk,
                        'tempat_lahir'  => 'Jakarta',
                        'tanggal_lahir' => $defaultBirthDate,
                        'alamat'        => 'Alamat belum diisi (menunggu data lengkap)',
                        'hp'            => $telp ?: null,
                        'kelas'         => $currentClass,
                        'notes'         => implode(' | ', $notesArr),
                    ];
                }
            }
        }

        return $this->saveStudents('SD', $students, $shouldReplace);
    }

    protected function handleSMA(bool $shouldReplace): int
    {
        $extractedPath = storage_path('app/sma_extracted');
        if (!file_exists($extractedPath . '/xl/worksheets/sheet1.xml')) {
            $this->error("File ekstrak SMA tidak ditemukan di: {$extractedPath}");
            return Command::FAILURE;
        }

        $sharedStrings = $this->loadSharedStrings($extractedPath);
        $sheetXml = simplexml_load_file($extractedPath . '/xl/worksheets/sheet1.xml');

        $currentClass = 'Kelas X';
        $students = [];

        foreach ($sheetXml->sheetData->row as $row) {
            $cols = $this->parseRowCells($row, $sharedStrings);

            $b = $cols['B'] ?? '';
            if (stripos($b, 'KELAS') !== false) {
                $currentClass = trim($b);
                continue;
            }

            if (empty($b) || !is_numeric($b)) {
                continue; // skip non-student rows
            }

            $nama = $cols['C'] ?? '';
            $nis = $cols['D'] ?? '';
            $jk = $cols['E'] ?? '';
            $ttl = $cols['F'] ?? '';
            $alamat = $cols['G'] ?? '';

            $parsedTtl = $this->parseIndonesianBirth($ttl);

            if (!empty($nama)) {
                $students[] = [
                    'nama'          => $nama,
                    'nis'           => preg_replace('/[^0-9]/', '', $nis),
                    'jk'            => strtoupper($jk) === 'P' ? 'P' : 'L',
                    'tempat_lahir'  => $parsedTtl['place'],
                    'tanggal_lahir' => $parsedTtl['date'],
                    'alamat'        => $alamat ?: 'Alamat belum diisi',
                    'kelas'         => $currentClass,
                    'hp'            => null,
                    'nisn'          => null,
                    'notes'         => "Kelas: {$currentClass}",
                ];
            }
        }

        return $this->saveStudents('SMA', $students, $shouldReplace);
    }

    protected function handleStandard(string $level, bool $shouldReplace): int
    {
        $extractedPath = storage_path('app/xlsx_extracted');
        $sheetFile = file_exists($extractedPath . '/xl/worksheets/sheet2.xml')
            ? $extractedPath . '/xl/worksheets/sheet2.xml'
            : $extractedPath . '/xl/worksheets/sheet1.xml';

        if (!file_exists($sheetFile)) {
            $this->error("File ekstrak tidak ditemukan di: {$sheetFile}");
            return Command::FAILURE;
        }

        $sharedStrings = $this->loadSharedStrings($extractedPath);
        $sheetXml = simplexml_load_file($sheetFile);
        $students = [];

        foreach ($sheetXml->sheetData->row as $row) {
            $rowNum = (int)$row['r'];
            if ($rowNum === 1) continue; // Skip header

            $cols = $this->parseRowCells($row, $sharedStrings);

            $nama = $cols['B'] ?? '';
            $nipd = $cols['C'] ?? '';
            $jk = $cols['D'] ?? '';
            $nisn = $cols['E'] ?? '';
            $tempatLahir = $cols['F'] ?? '';
            $tglLahir = $cols['G'] ?? '';
            $alamat = $cols['J'] ?? '';
            $rt = $cols['K'] ?? '';
            $rw = $cols['L'] ?? '';
            $kelurahan = $cols['N'] ?? '';
            $kecamatan = $cols['O'] ?? '';
            $hp = $cols['T'] ?? '';
            $kelas = $cols['U'] ?? '';

            if (!empty($nama) && !empty($tglLahir)) {
                $nis = $nipd ?: $nisn;
                $students[] = [
                    'nama'          => $nama,
                    'nis'           => preg_replace('/[^0-9]/', '', $nis),
                    'nipd'          => $nipd,
                    'nisn'          => $nisn,
                    'jk'            => strtoupper($jk) === 'P' ? 'P' : 'L',
                    'tempat_lahir'  => $tempatLahir,
                    'tanggal_lahir' => $tglLahir, // YYYY-MM-DD
                    'alamat'        => trim("{$alamat} " . ($rt ? "RT {$rt}" : "") . ($rw ? "/RW {$rw}" : "") . " {$kelurahan} {$kecamatan}"),
                    'hp'            => $hp,
                    'kelas'         => $kelas,
                    'notes'         => (!empty($nisn) ? "NISN: {$nisn} | " : "") . ($kelas ? "Kelas: {$kelas}" : ""),
                ];
            }
        }

        return $this->saveStudents($level, $students, $shouldReplace);
    }

    protected function saveStudents(string $level, array $students, bool $shouldReplace): int
    {
        $count = count($students);
        $this->info("Ditemukan {$count} data siswa {$level}.");

        if ($count === 0) {
            $this->warn("Tidak ada data siswa yang valid.");
            return Command::FAILURE;
        }

        Role::firstOrCreate(['name' => 'anggota']);

        DB::beginTransaction();
        try {
            if ($shouldReplace) {
                $this->warn("Menghapus data anggota lama jenjang {$level}...");
                $oldMembers = Member::where('member_type', "Siswa {$level}")->get();

                foreach ($oldMembers as $om) {
                    if ($om->user && !$om->user->hasAnyRole(['super-admin', 'pustakawan'])) {
                        $om->user->delete();
                    }
                    $om->forceDelete();
                }
            }

            $imported = 0;
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $levelCodes = [
                'SD'  => '01',
                'SMP' => '02',
                'SMA' => '03',
            ];
            $lvlCode = $levelCodes[$level] ?? '02';

            foreach ($students as $s) {
                $rawNis = preg_replace('/[^0-9]/', '', $s['nis']);
                if (empty($rawNis) && !empty($s['nisn'])) {
                    $rawNis = preg_replace('/[^0-9]/', '', $s['nisn']);
                }

                // Format Kode Anggota: 26 (Tahun) + 01/02/03 (Jenjang) + Urutan 4 digit
                $seq = $imported + 1;
                $memberCode = '26' . $lvlCode . str_pad($seq, 4, '0', STR_PAD_LEFT);
                $barcode = $memberCode;
                $birthDate = $s['tanggal_lahir']; // Format: YYYY-MM-DD

                // Create or update member
                $member = Member::updateOrCreate(
                    ['member_code' => $memberCode],
                    [
                        'name'            => $s['nama'],
                        'identity_number' => $rawNis,
                        'identity_type'   => 'NIS',
                        'gender'          => $s['jk'],
                        'birth_place'     => $s['tempat_lahir'] ?: 'Jakarta',
                        'birth_date'      => $birthDate,
                        'address'         => $s['alamat'] ?: 'Alamat belum diisi',
                        'phone'           => $s['hp'] ?: null,
                        'institution'     => "{$level}" . ($s['kelas'] ? " - {$s['kelas']}" : ""),
                        'member_type'     => "Siswa {$level}",
                        'barcode'         => $barcode,
                        'register_date'   => today(),
                        'expired_date'    => today()->addYears(3),
                        'is_active'       => true,
                        'notes'           => $s['notes'] ?? '',
                    ]
                );

                // Create or update portal user account with numeric username
                $username = $memberCode;
                $email = "{$memberCode}@makarya.local";

                $user = User::updateOrCreate(
                    ['member_id' => $member->id],
                    [
                        'name'      => $s['nama'],
                        'username'  => $username,
                        'email'     => $email,
                        'password'  => Hash::make($birthDate), // Password is YYYY-MM-DD
                        'is_active' => true,
                    ]
                );

                if (!$user->hasRole('anggota')) {
                    $user->assignRole('anggota');
                }

                $imported++;
                $bar->advance();
            }

            $bar->finish();
            DB::commit();

            $this->newLine(2);
            $this->info("✅ Berhasil mengimpor {$imported} data Siswa {$level} ke database!");
            $this->info("   - Kode Anggota & Barcode: Angka diawali '26' (contoh: 26{$students[0]['nis']})");
            $this->info("   - Password login portal: Format YYYY-MM-DD (contoh: {$students[0]['tanggal_lahir']})");
            $this->info("   - Siswa bisa login menggunakan: Nama Lengkap ('{$students[0]['nama']}') atau NIS ('{$students[0]['nis']}') atau Kode ('26{$students[0]['nis']}')");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal import data: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function loadSharedStrings(string $extractedPath): array
    {
        $sharedStrings = [];
        if (file_exists($extractedPath . '/xl/sharedStrings.xml')) {
            $xml = simplexml_load_file($extractedPath . '/xl/sharedStrings.xml');
            foreach ($xml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string)$si->t;
                } elseif (isset($si->r)) {
                    $t = '';
                    foreach ($si->r as $r) {
                        $t .= (string)$r->t;
                    }
                    $sharedStrings[] = $t;
                } else {
                    $sharedStrings[] = '';
                }
            }
        }
        return $sharedStrings;
    }

    protected function parseRowCells($row, array $sharedStrings): array
    {
        $cols = [];
        foreach ($row->c as $c) {
            $cellRef = (string)$c['r'];
            $cellType = (string)$c['t'];
            $val = (string)$c->v;

            if ($cellType === 's') {
                $val = $sharedStrings[(int)$val] ?? $val;
            }

            $colLetter = preg_replace('/[0-9]/', '', $cellRef);
            $cols[$colLetter] = trim($val);
        }
        return $cols;
    }

    protected function parseIndonesianBirth(string $str): array
    {
        $months = [
            'januari' => '01', 'jan' => '01',
            'februari' => '02', 'pebruari' => '02', 'feb' => '02',
            'maret' => '03', 'mar' => '03',
            'april' => '04', 'apr' => '04',
            'mei' => '05', 'may' => '05',
            'juni' => '06', 'jun' => '06',
            'juli' => '07', 'jul' => '07',
            'agustus' => '08', 'agu' => '08', 'ags' => '08', 'aug' => '08',
            'september' => '09', 'sep' => '09',
            'oktober' => '10', 'okt' => '10', 'oct' => '10',
            'november' => '11', 'nop' => '11', 'nov' => '11',
            'desember' => '12', 'des' => '12', 'dec' => '12',
        ];

        $str = trim($str);
        $place = 'Jakarta';
        $day = '01';
        $month = '01';
        $year = '2010';

        if (preg_match('/^(.*?)[,\.]\s*(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4})/i', $str, $matches)) {
            $place = trim($matches[1]);
            $day = str_pad(trim($matches[2]), 2, '0', STR_PAD_LEFT);
            $monthName = strtolower(trim($matches[3]));
            $month = $months[$monthName] ?? '01';
            $year = trim($matches[4]);
        }

        return [
            'place' => $place,
            'date' => "{$year}-{$month}-{$day}"
        ];
    }
}
