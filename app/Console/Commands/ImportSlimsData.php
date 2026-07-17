<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ImportSlimsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:slims';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate database tables and records from SLiMS (akasia) to Makarya application structure';

    /**
     * Sanitize zero dates to null to prevent SQL strict format errors
     */
    private function sanitizeDate($date)
    {
        if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return null;
        }
        return $date;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai Proses Migrasi Data SLiMS (akasia) ke Makarya...');

        // Adjust column sizes if they are too small for SLiMS data to prevent truncation errors
        $this->info("🔧 Menyesuaikan tipe kolom database agar menampung data SLiMS...");
        DB::statement('ALTER TABLE books MODIFY title TEXT NOT NULL');
        DB::statement('ALTER TABLE books MODIFY subtitle TEXT NULL');
        DB::statement('ALTER TABLE books MODIFY publication_year VARCHAR(50) NULL');
        DB::statement('ALTER TABLE members MODIFY phone VARCHAR(100) NULL');
        DB::statement('ALTER TABLE book_items MODIFY accession_number VARCHAR(255) NOT NULL');

        // Drop unique constraint on book_items.accession_number to allow duplicate inventory codes
        try {
            DB::statement('ALTER TABLE book_items DROP INDEX book_items_accession_number_unique');
        } catch (\Exception $e) {
            // Already dropped
        }
        try {
            DB::statement('ALTER TABLE book_items ADD INDEX book_items_accession_number_index (accession_number)');
        } catch (\Exception $e) {
            // Already added
        }

        // Nonaktifkan pemeriksaan foreign key sementara untuk truncate data
        Schema::disableForeignKeyConstraints();

        // 1. Truncate tabel-tabel tujuan Makarya
        $tablesToClean = [
            'circulations',
            'book_items',
            'book_author',
            'book_subject',
            'books',
            'authors',
            'publishers',
            'subjects',
            'locations',
            'members',
        ];

        foreach ($tablesToClean as $table) {
            $this->info("🧹 Mengosongkan tabel: {$table}");
            DB::table($table)->truncate();
        }

        // Hapus akun pengguna lama dengan role 'anggota' agar tidak bentrok
        $this->info("🧹 Menghapus akun user lama dengan role 'anggota'...");
        $anggotaUsers = \App\Models\User::role('anggota')->withTrashed()->get();
        foreach ($anggotaUsers as $user) {
            $user->forceDelete();
        }

        // 2. Migrasi Pengarang (mst_author ➔ authors)
        $this->info('➔ Mengimpor Pengarang...');
        $authors = DB::table('akasia.mst_author')->get();
        foreach ($authors as $author) {
            DB::table('authors')->insert([
                'id'         => $author->author_id,
                'name'       => $author->author_name,
                'type'       => 'personal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('✓ Berhasil mengimpor ' . $authors->count() . ' data pengarang.');

        // 3. Migrasi Penerbit (mst_publisher ➔ publishers)
        $this->info('➔ Mengimpor Penerbit...');
        $publishers = DB::table('akasia.mst_publisher')->get();
        foreach ($publishers as $pub) {
            DB::table('publishers')->insert([
                'id'         => $pub->publisher_id,
                'name'       => $pub->publisher_name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('✓ Berhasil mengimpor ' . $publishers->count() . ' data penerbit.');

        // 4. Migrasi Subyek (mst_topic ➔ subjects)
        $this->info('➔ Mengimpor Subyek/Kategori...');
        $topics = DB::table('akasia.mst_topic')->get();
        foreach ($topics as $topic) {
            DB::table('subjects')->insert([
                'id'         => $topic->topic_id,
                'name'       => $topic->topic,
                'ddc'        => $topic->classification ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('✓ Berhasil mengimpor ' . $topics->count() . ' data subyek.');

        // 5. Migrasi Lokasi (mst_location ➔ locations)
        $this->info('➔ Mengimpor Lokasi Rak...');
        $locations = DB::table('akasia.mst_location')->get();
        foreach ($locations as $loc) {
            DB::table('locations')->insert([
                'code'       => $loc->location_id,
                'name'       => $loc->location_name ?? 'Lokasi ' . $loc->location_id,
                'description'=> 'Lokasi migrasi dari SLiMS',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('✓ Berhasil mengimpor ' . $locations->count() . ' data lokasi rak.');

        // 6. Migrasi Buku/Bibliografi (biblio ➔ books)
        $this->info('➔ Mengimpor Bibliografi Buku...');
        $books = DB::table('akasia.biblio')->get();
        foreach ($books as $book) {
            DB::table('books')->insert([
                'id'               => $book->biblio_id,
                'title'            => $book->title,
                'subtitle'         => $book->sor ?? null,
                'isbn'             => $book->isbn_issn,
                'call_number'      => $book->call_number,
                'ddc'              => $book->classification,
                'edition'          => $book->edition,
                'publication_year' => $book->publish_year,
                'notes'            => $book->notes,
                'cover_image'      => $book->image ? 'covers/' . $book->image : null,
                'publisher_id'     => $book->publisher_id,
                'created_at'       => $this->sanitizeDate($book->input_date) ?? now(),
                'updated_at'       => $this->sanitizeDate($book->last_update) ?? now(),
            ]);
        }
        $this->info('✓ Berhasil mengimpor ' . $books->count() . ' data buku.');

        // 7. Migrasi Relasi Buku-Pengarang (biblio_author ➔ book_author)
        $this->info('➔ Mengimpor Relasi Buku-Pengarang...');
        $bookAuthors = DB::table('akasia.biblio_author')->get();
        foreach ($bookAuthors as $ba) {
            DB::table('book_author')->insert([
                'book_id'   => $ba->biblio_id,
                'author_id' => $ba->author_id,
                'role'      => 'author',
                'order'     => $ba->level ?? 1,
            ]);
        }
        $this->info('✓ Berhasil mengimpor ' . $bookAuthors->count() . ' relasi buku-pengarang.');

        // 8. Migrasi Relasi Buku-Subyek (biblio_topic ➔ book_subject)
        $this->info('➔ Mengimpor Relasi Buku-Subyek...');
        $bookTopics = DB::table('akasia.biblio_topic')->get();
        foreach ($bookTopics as $bt) {
            DB::table('book_subject')->insert([
                'book_id'    => $bt->biblio_id,
                'subject_id' => $bt->topic_id,
            ]);
        }
        $this->info('✓ Berhasil mengimpor ' . $bookTopics->count() . ' relasi buku-subyek.');

        // 9. Migrasi Eksemplar Buku (item ➔ book_items)
        $this->info('➔ Mengimpor Eksemplar Fisik Buku...');
        $items = DB::table('akasia.item')->get();
        $importedItemsCount = 0;
        
        foreach ($items as $item) {
            // Dapatkan ID lokasi internal di Makarya berdasarkan kode lokasi SLiMS
            $dbLoc = DB::table('locations')->where('code', $item->location_id)->first();
            $locId = $dbLoc ? $dbLoc->id : null;

            // Map status buku SLiMS ke Makarya
            $status = 'Tersedia';
            if ($item->item_status_id == 'U' || $item->item_status_id == 'R') {
                $status = 'Perbaikan';
            } elseif ($item->item_status_id == 'LO' || $item->item_status_id == 'H') {
                $status = 'Hilang';
            }

            DB::table('book_items')->insert([
                'id'                 => $item->item_id,
                'book_id'            => $item->biblio_id,
                'barcode'            => $item->item_code,
                'accession_number'   => $item->inventory_code ?? $item->item_code,
                'location_id'        => $locId,
                'condition'          => 'Baik',
                'status'             => $status,
                'acquisition_date'   => $this->sanitizeDate($item->received_date),
                'acquisition_price'  => floatval($item->price),
                'acquisition_source' => $item->source == 1 ? 'Beli' : 'Hibah',
                'notes'              => $item->invoice ?? null,
                'created_at'         => $this->sanitizeDate($item->input_date) ?? now(),
                'updated_at'         => $this->sanitizeDate($item->last_update) ?? now(),
            ]);
            $importedItemsCount++;
        }
        $this->info('✓ Berhasil mengimpor ' . $importedItemsCount . ' data eksemplar.');

        // 10. Migrasi Anggota (member ➔ members) + pembuatan Akun Login
        $this->info('➔ Mengimpor Anggota & Kredensial Akun...');
        $members = DB::table('akasia.member')
            ->leftJoin('akasia.mst_member_type', 'member.member_type_id', '=', 'mst_member_type.member_type_id')
            ->select('member.*', 'mst_member_type.member_type_name')
            ->get();
        
        $importedMembersCount = 0;
        foreach ($members as $member) {
            // Map Gender: 1 -> L (Laki-laki), 0 -> P (Perempuan)
            $gender = ($member->gender == 1) ? 'L' : 'P';

            // Insert data anggota ke Makarya
            DB::table('members')->insert([
                'member_code'     => $member->member_id,
                'name'            => $member->member_name,
                'email'           => $member->member_email,
                'phone'           => $member->member_phone,
                'identity_number' => $member->identity_number ?? null,
                'identity_type'   => 'KTP',
                'gender'          => $gender,
                'address'         => $member->member_address,
                'member_type'     => $member->member_type_name ?? 'Umum',
                'register_date'   => $this->sanitizeDate($member->birth_date), // birth_date is in SLiMS layout here or register_date if defined
                'expired_date'    => now()->addYears(5)->toDateString(),
                'is_active'       => true,
                'photo'           => $member->member_image ? 'members/' . $member->member_image : null,
                'barcode'         => 'M' . $member->member_id,
                'created_at'      => $this->sanitizeDate($member->input_date) ?? now(),
                'updated_at'      => $this->sanitizeDate($member->last_update) ?? now(),
            ]);

            // Dapatkan ID anggota internal
            $dbMember = DB::table('members')->where('member_code', $member->member_id)->first();

            $importedMembersCount++;
        }
        $this->info('✓ Berhasil mengimpor ' . $importedMembersCount . ' data anggota.');

        // 11. Migrasi Transaksi Sirkulasi (loan ➔ circulations)
        $this->info('➔ Mengimpor Transaksi Sirkulasi...');
        $loans = DB::table('akasia.loan')->get();
        $importedLoansCount = 0;

        foreach ($loans as $loan) {
            // Cari data internal item & anggota
            $dbItem = DB::table('book_items')->where('barcode', $loan->item_code)->first();
            $dbMember = DB::table('members')->where('member_code', $loan->member_id)->first();

            if ($dbItem && $dbMember) {
                // Map status sirkulasi: is_return == 1 -> Dikembalikan, else check due_date
                $status = 'Dipinjam';
                if ($loan->is_return == 1) {
                    $status = 'Dikembalikan';
                } elseif (now()->toDateString() > $loan->due_date) {
                    $status = 'Terlambat';
                }

                $loanDate = $this->sanitizeDate($loan->loan_date) ?? now()->toDateString();
                $dueDate = $this->sanitizeDate($loan->due_date) ?? now()->addDays(14)->toDateString();

                DB::table('circulations')->insert([
                    'transaction_code'=> 'TRX-SLIMS-' . str_pad($loan->loan_id, 6, '0', STR_PAD_LEFT),
                    'member_id'       => $dbMember->id,
                    'book_item_id'    => $dbItem->id,
                    'user_id'         => 1, // Default petugas Administrator
                    'loan_date'       => $loanDate,
                    'due_date'        => $dueDate,
                    'return_date'     => $this->sanitizeDate($loan->return_date),
                    'renewal_count'   => $loan->renewed ?? 0,
                    'status'          => $status,
                    'fine_amount'     => 0,
                    'fine_paid'       => false,
                    'created_at'      => $this->sanitizeDate($loan->input_date) ?? now(),
                    'updated_at'      => $this->sanitizeDate($loan->last_update) ?? now(),
                ]);

                // Jika statusnya dipinjam atau terlambat, set status eksemplar menjadi 'Dipinjam'
                if ($status == 'Dipinjam' || $status == 'Terlambat') {
                    DB::table('book_items')->where('id', $dbItem->id)->update(['status' => 'Dipinjam']);
                }

                $importedLoansCount++;
            }
        }
        $this->info('✓ Berhasil mengimpor ' . $importedLoansCount . ' transaksi peminjaman/sirkulasi.');

        // Recreate default testing member user
        $this->info("🔑 Membuat ulang akun anggota default untuk testing (anggota / anggota123)...");
        $existingAnggota = \App\Models\User::withTrashed()->where('username', 'anggota')->first();
        if ($existingAnggota) {
            $existingAnggota->forceDelete();
        }
        
        $anggotaUser = \App\Models\User::create([
            'name'     => 'Anggota Perpustakaan',
            'username' => 'anggota',
            'email'    => 'anggota@makarya.local',
            'password' => bcrypt('anggota123'),
            'is_active'=> true,
        ]);
        $anggotaUser->assignRole('anggota');

        // Aktifkan kembali pemeriksaan foreign key
        Schema::enableForeignKeyConstraints();

        $this->info('🎉 PROSES MIGRASI SELESAI DENGAN SUKSES!');
    }
}
