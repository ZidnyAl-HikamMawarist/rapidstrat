# 🚀 RapidStrat

[![Latest Version](https://img.shields.io/badge/version-1.1.0-blue.svg)](https://packagist.org/packages/zidnyal-hikammawarist/rapidstrat)
[![Laravel Version](https://img.shields.io/badge/laravel-10.x%20%7C%2011.x%20%7C%2012.x-red.svg)](https://laravel.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-emerald.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-purple.svg)](https://php.net)

**RapidStrat** adalah starter scaffolding package untuk ekosistem Laravel yang dirancang untuk mempercepat perakitan fondasi aplikasi berbasis peran (*Multi-Role Auth*), tata letak antarmuka modern (*Tailwind CSS Admin Panel*), laporan siap cetak (*PDF Export Engine*), serta blueprint CRUD yang terdokumentasi rapi untuk kebutuhan Uji Kompetensi Keahlian (UjiKom) maupun prototyping cepat.

---

## 🌟 Fitur Utama

- 🔐 **Multi-Role Authentication**: Sistem login & registrasi dengan pemisahan peran bawaan (`admin`, `petugas`, `user`).
- 🛡️ **Role Guard Middleware**: Middleware `CheckRole` yang aman dan mudah diterapkan pada rute aplikasi.
- 🖥️ **Tailwind CSS Admin Panel**: Tampilan dashboard responsif, modern, dan estetik dengan sidebar dinamis, kartu metrik statistik, dan notifikasi SweetAlert2 (termasuk validasi form error).
- 📄 **Universal PDF Report Engine**: Filter rentang tanggal otomatis dengan kop surat resmi instansi, tanda tangan penanggung jawab, serta fallback cetak langsung browser (`window.print`).
- 📦 **CRUD Blueprint Stubs**: Template controller, formulir input, dan tabel manajemen data yang siap disesuaikan untuk berbagai studi kasus (Buku, Mobil, Barang, Pengaduan, dll).
- 📖 **Dokumentasi & Komentar Kode Bersih**: Setiap baris kode controller, model, dan view dilengkapi komentar terstruktur dan berbobot (*clean code*) yang menjadi nilai tambah besar saat pengujian.
- 🔌 **Kesiapan Mode Offline**: Disertai panduan antisipasi jika ujian dilakukan di lab komputer sekolah tanpa koneksi internet.

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

### 3. Migrasi & Seeder Database
```bash
php artisan migrate
php artisan db:seed --class=UserRoleSeeder
```

*(Catatan: Pendaftaran middleware `role` di `bootstrap/app.php` sudah ditangani secara otomatis oleh installer).*

---

## 🔑 Akun Demo Pengujian Bawaan
- **Admin**: `admin@rapidstrat.test` (Password: `password`)
- **Petugas**: `petugas@rapidstrat.test` (Password: `password`)
- **User**: `user@rapidstrat.test` (Password: `password`)

---

## 🔌 Kesiapan UjiKom Mode Offline (Tanpa Internet)
Jika lab sekolah mematikan koneksi internet saat ujian:
1. Pastikan aset CSS/JS lokal (Tailwind & FontAwesome) tersimpan di folder `public/`.
2. Jika belum menginstal `barryvdh/laravel-dompdf`, fitur laporan RapidStrat secara otomatis beralih ke dialog cetak browser (`window.print`) yang menghasilkan dokumen cetak A4 yang sama rapinya.
3. Panduan lengkap dan contekan jawaban penguji tersedia di file `./PANDUAN_UJIKOM.md`.

---

## 📄 Lisensi
Package ini dirilis di bawah lisensi [MIT License](LICENSE).  
Dikembangkan oleh **Zidny Al-Hikam Mawarist**.
