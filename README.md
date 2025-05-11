# Alexandra-Tugas-8
Tugas 8 Kelas A Pemrograman Web Sistem Informasi

# Petunjuk untuk Project Laravel Website Peminjaman Buku

## Persyaratan Sistem
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM (opsional, untuk pengembangan frontend)

## Langkah Instalasi
1. Clone repository ini
2. Buka terminal, masuk ke direktori project
3. Jalankan `composer install`
4. Copy file `.env.example` menjadi `.env`
5. Sesuaikan konfigurasi database di file `.env`
6. Jalankan `php artisan key:generate`
7. Jalankan `php artisan migrate`
8. Jalankan `php artisan serve`

## Fitur Aplikasi
- CRUD data peminjaman buku
- Validasi form
- AJAX untuk operasi tanpa reload
- Tampilan responsif dengan Tailwind CSS

## Struktur Database
Aplikasi menggunakan satu tabel utama:
- `peminjaman`: menyimpan data peminjaman buku

## Penggunaan
1. Akses aplikasi melalui `http://localhost:8000`
2. Gunakan form untuk menambah data peminjaman
3. Data dapat diedit dan dihapus melalui tombol aksi di tabel
