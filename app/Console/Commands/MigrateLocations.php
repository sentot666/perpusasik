<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\BookItem;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class MigrateLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perpus:migrate-locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate DDC locations and migrate book items to correct shelves based on call number';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Menyiapkan Rak (Master Lokasi)...");

        $racks = [
            '000' => 'Karya Umum, Komputer & Informasi',
            '100' => 'Filsafat & Psikologi',
            '200' => 'Agama',
            '300' => 'Ilmu Sosial',
            '400' => 'Bahasa',
            '500' => 'Sains & Matematika',
            '600' => 'Teknologi & Ilmu Terapan',
            '700' => 'Seni & Rekreasi',
            '800' => 'Sastra',
            '900' => 'Sejarah & Geografi',
            'F' => 'Fiksi',
            'R' => 'Referensi / Rujukan',
        ];

        $locationMap = [];

        DB::beginTransaction();
        try {
            foreach ($racks as $code => $name) {
                // Gunakan kode rak as code, misal RAK-000 atau cuma 000
                $locationMap[$code] = Location::firstOrCreate(
                    ['code' => $code],
                    ['name' => $name, 'description' => "Rak otomatis untuk klasifikasi $code"]
                )->id;
            }

            // Default location for items that don't match anything
            $defaultLocation = Location::firstOrCreate(
                ['code' => 'SL'],
                ['name' => 'Perpustakaan Katolik St Paulus', 'description' => 'Lokasi default']
            )->id;

            $this->info("Rak berhasil disiapkan. Memulai migrasi eksemplar...");

            $items = BookItem::with('book')->get();
            $moved = 0;

            foreach ($items as $item) {
                if (!$item->book) continue;

                $callNumber = trim($item->book->call_number ?? $item->book->ddc ?? '');
                $firstChar = strtoupper(substr($callNumber, 0, 1));
                
                $targetCode = 'SL'; // default

                if (is_numeric($firstChar)) {
                    $targetCode = $firstChar . '00';
                } elseif ($firstChar === 'F') {
                    $targetCode = 'F';
                } elseif ($firstChar === 'R') {
                    $targetCode = 'R';
                }

                if (isset($locationMap[$targetCode])) {
                    $item->location_id = $locationMap[$targetCode];
                    $item->save();
                    $moved++;
                }
            }

            DB::commit();
            $this->info("Selesai! Berhasil memindahkan $moved eksemplar ke rak yang sesuai berdasarkan Nomor Panggil.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal: " . $e->getMessage());
        }
    }
}
