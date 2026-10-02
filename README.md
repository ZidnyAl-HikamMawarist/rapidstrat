# 🚀 RapidStrat

[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://packagist.org/packages/zidnyal-hikammawarist/rapidstrat)
[![Laravel Version](https://img.shields.io/badge/laravel-10.x%20%7C%2011.x%20%7C%2012.x-red.svg)](https://laravel.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-emerald.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-purple.svg)](https://php.net)

**RapidStrat** adalah starter scaffolding package untuk ekosistem Laravel yang dirancang untuk mempercepat perakitan fondasi aplikasi berbasis peran (*Multi-Role Auth*), tata letak antarmuka modern (*Tailwind CSS Admin Panel*), laporan siap cetak (*PDF Export Engine*), serta blueprint CRUD yang terstruktur dan mudah dijelaskan.

---

## 🌟 Fitur Utama

- 🔐 **Multi-Role Authentication**: Sistem login & registrasi dengan pemisahan peran bawaan (`admin`, `petugas`, `user`).
- 🛡️ **Role Guard Middleware**: Middleware `CheckRole` yang aman dan mudah diterapkan pada rute aplikasi.
- 🖥️ **Tailwind CSS Admin Panel**: Tampilan dashboard responsif, modern, dan estetik dengan sidebar dinamis, kartu metrik statistik, dan notifikasi SweetAlert2.
- 📄 **Universal PDF Report Engine**: Filter rentang tanggal otomatis dengan kop surat resmi instansi dan tanda tangan penanggung jawab.
- 📦 **CRUD Blueprint Stubs**: Template controller, formulir input, dan tabel manajemen data yang siap disesuaikan untuk berbagai studi kasus.
- 📘 **Panduan Arsitektur & Tanya Jawab**: Dilengkapi `PANDUAN_UJIKOM.md` yang menjelaskan alur MVC dan referensi jawaban lisan saat pengujian kompetensi.

---

## 📥 Panduan Instalasi

### 1. Pasang Package via Composer
Jalankan perintah berikut di proyek Laravel Anda:

```bash
composer require zidnyal-hikammawarist/rapidstrat
```

### 2. Jalankan Installer Interaktif
Jalankan artisan command untuk memilih modul yang ingin Anda pasang:

```bash
php artisan rapidstrat:install
```

Pilih modul sesuai kebutuhan atau tekan `Enter` untuk memasang paket lengkap (*Full Package*).

### 3. Daftarkan Middleware Role
Pada Laravel 11/12, buka `bootstrap/app.php` dan daftarkan alias middleware:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

### 4. Migrasi & Seeder Database
```bash
php artisan migrate
php artisan db:seed --class=UserRoleSeeder
```

### 5. Akun Pengujian Bawaan
- **Admin**: `admin@rapidstrat.test` (Password: `password`)
- **Petugas**: `petugas@rapidstrat.test` (Password: `password`)
- **User**: `user@rapidstrat.test` (Password: `password`)

---

## 📄 Lisensi
Package ini dirilis di bawah lisensi [MIT License](LICENSE).
Dikembangkan oleh **Zidny Al-Hikam Mawarist**.
