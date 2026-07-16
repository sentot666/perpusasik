<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Users (staff/librarian) ───────────────────────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── Members (anggota perpustakaan) ────────────────────────────────────
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code', 50)->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('identity_number', 50)->nullable(); // KTP/NIM/NIS
            $table->string('identity_type', 20)->default('KTP'); // KTP, NIM, NIS, dsb
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->string('education', 50)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('institution', 150)->nullable();
            $table->string('member_type', 50)->default('Umum'); // Umum, Pelajar, Mahasiswa, Pegawai
            $table->date('register_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->string('barcode', 100)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index('member_code');
            $table->index('name');
        });

        // ── Book Authors ──────────────────────────────────────────────────────
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 20)->default('personal'); // personal, organization
            $table->text('biography')->nullable();
            $table->timestamps();

            $table->index('name');
        });

        // ── Publishers ────────────────────────────────────────────────────────
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('website')->nullable();
            $table->timestamps();

            $table->index('name');
        });

        // ── Subjects / Topics ─────────────────────────────────────────────────
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ddc', 30)->nullable(); // Dewey Decimal Classification
            $table->timestamps();
        });

        // ── Locations (rak/koleksi) ───────────────────────────────────────────
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ── Books (bibliografi/katalog) ───────────────────────────────────────
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('isbn', 30)->nullable();
            $table->string('isbn13', 30)->nullable();
            $table->string('call_number', 100)->nullable();    // Nomor Panggil
            $table->string('ddc', 30)->nullable();             // DDC
            $table->string('edition', 50)->nullable();
            $table->string('language', 10)->default('id');
            $table->string('publication_year', 10)->nullable();
            $table->string('place_of_publication', 100)->nullable();
            $table->integer('pages')->nullable();
            $table->string('dimensions', 50)->nullable();
            $table->string('series_title')->nullable();
            $table->string('series_number', 50)->nullable();
            $table->text('abstract')->nullable();
            $table->text('notes')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('collection_type', 30)->default('Buku Teks'); // Buku Teks, Referensi, Majalah, dsb
            $table->boolean('is_active')->default(true);
            $table->foreignId('publisher_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
            $table->index('isbn');
            $table->index('call_number');
        });

        // ── Book ↔ Authors (pivot) ─────────────────────────────────────────────
        Schema::create('book_author', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->string('role', 30)->default('author'); // author, editor, translator, illustrator
            $table->integer('order')->default(1);
        });

        // ── Book ↔ Subjects (pivot) ────────────────────────────────────────────
        Schema::create('book_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
        });

        // ── Book Items / Exemplar (eksemplar fisik) ───────────────────────────
        Schema::create('book_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('barcode', 100)->unique();
            $table->string('accession_number', 50)->unique(); // Nomor Induk
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('condition', 20)->default('Baik'); // Baik, Rusak, Hilang
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Dipesan', 'Perbaikan', 'Hilang'])->default('Tersedia');
            $table->date('acquisition_date')->nullable();
            $table->decimal('acquisition_price', 12, 2)->nullable();
            $table->string('acquisition_source', 50)->nullable(); // Beli, Hibah, dsb
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('barcode');
            $table->index('accession_number');
            $table->index('status');
        });

        // ── Circulation (sirkulasi pinjam/kembali) ────────────────────────────
        Schema::create('circulations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 50)->unique();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // petugas yang melayani
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->integer('renewal_count')->default(0);
            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat', 'Hilang'])->default('Dipinjam');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->timestamp('fine_paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('member_id');
            $table->index('book_item_id');
            $table->index('status');
            $table->index('loan_date');
        });

        // ── Book Reservations ─────────────────────────────────────────────────
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_item_id')->nullable()->constrained()->nullOnDelete();
            $table->date('reserve_date');
            $table->date('expired_date')->nullable();
            $table->enum('status', ['Menunggu', 'Siap', 'Dibatalkan', 'Selesai'])->default('Menunggu');
            $table->timestamps();
        });

        // ── Library Settings ──────────────────────────────────────────────────
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->string('type', 20)->default('text'); // text, number, boolean, json
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // ── Cache & Session tables ────────────────────────────────────────────
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('circulations');
        Schema::dropIfExists('book_items');
        Schema::dropIfExists('book_subject');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('books');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('members');
        Schema::dropIfExists('users');
    }
};
