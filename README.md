# Panduan Menjalankan Program Perpustakaan Makarya

Berikut adalah langkah-langkah untuk menjalankan aplikasi Perpustakaan Makarya (berbasis Laravel) di lingkungan pengembangan lokal Anda (misalnya menggunakan Laragon, XAMPP, atau Laravel Valet).

## Persyaratan Sistem

Pastikan sistem Anda telah menginstal perangkat lunak berikut:
- **PHP** (minimal versi 8.1 atau yang disarankan untuk Laravel 11+)
- **Composer** (untuk mengelola dependensi PHP)
- **Node.js & npm** (untuk mengelola dependensi Frontend seperti Vite dan Tailwind CSS)
- **MySQL/MariaDB** (sebagai database server)

## Langkah-langkah Instalasi & Konfigurasi

### 1. Clone / Siapkan Direktori Proyek
Pastikan terminal Anda sudah berada di dalam direktori proyek ini.
```bash
cd c:\laragon\www\laravel
```

### 2. Instal Dependensi PHP
Jalankan perintah Composer untuk menginstal semua library PHP yang dibutuhkan oleh Laravel:
```bash
composer install
```

### 3. Instal Dependensi Frontend (Node.js)
Jalankan perintah npm untuk menginstal library Javascript (Vite, TailwindCSS, Alpine.js, dll):
```bash
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` dan ubah namanya menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan konfigurasi koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan Anda telah membuat database kosong di MySQL dengan nama yang sesuai di `DB_DATABASE` sebelum lanjut ke langkah berikutnya).*

### 5. Generate Application Key
Jalankan perintah berikut untuk menghasilkan kunci keamanan aplikasi:
```bash
php artisan key:generate
```

### 6. Migrasi dan Seeding Database
Jalankan migrasi untuk membuat tabel-tabel di database, beserta data awal (seeder) yang mencakup Role, Permission, dan Akun Default:
```bash
php artisan migrate --seed
```

## Menjalankan Aplikasi

Aplikasi ini menggunakan Vite untuk kompilasi aset frontend. Oleh karena itu, Anda perlu menjalankan **dua terminal** secara bersamaan.

**Terminal 1 (Menjalankan server PHP/Laravel):**
```bash
php artisan serve
```
*(Aplikasi biasanya akan berjalan di `http://127.0.0.1:8000`)*

**Terminal 2 (Menjalankan server Vite untuk aset frontend):**
Buka tab terminal baru, arahkan ke folder proyek, dan jalankan:
```bash
npm run dev
```

## Akun Login Default

Setelah proses migrasi dan seeding (`php artisan migrate --seed`) berhasil, Anda dapat masuk menggunakan salah satu akun default berikut:

| Peran (Role) | Username / Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `admin` | `admin123` |
| **Pustakawan** | `pustakawan` | `pustakawan123` |
| **Anggota** | `anggota` | `anggota123` |

Selamat mencoba!
