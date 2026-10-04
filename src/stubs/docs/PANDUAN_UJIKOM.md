# 📘 PANDUAN ARSITEKTUR, MODE OFFLINE & CONTEKAN JAWABAN PENGUJI (RAPIDSTRAT)

Dokumen ini adalah **peta navigasi, panduan teknis, dan contekan resmi** untuk membantumu memahami seluruh alur kode, mempersiapkan kondisi darurat offline, dan menjawab semua pertanyaan penguji saat Ujikom (UKK RPL).

---

## 🗺️ 1. PETA FILE & KONEKSI ARSITEKTUR

Aplikasi ini menggunakan pola arsitektur **MVC (Model - View - Controller)** dengan pemisahan peran (*Multi-Role Access Control*).

```text
[ Browser / Pengguna ]
        │
        ▼ (HTTP Request)
[ routes/web.php ]  ────────►  [ CheckRole Middleware ] (Cek apakah role sesuai)
        │
        ▼ (Jika lolos verifikasi)
[ Controller ]  ◄───────────►  [ Model (Eloquent) ] ◄──► [ Database MySQL ]
        │
        ▼ (Mengirim Data ke Template)
[ View (Blade + Tailwind) ]
```

### Daftar File Utama & Fungsinya:
1. **Autentikasi & Login:**
   - `app/Http/Controllers/AuthController.php` $\rightarrow$ Mengatur validasi login, register, enkripsi password Bcrypt, dan redirect otomatis sesuai role.
   - `resources/views/auth/login.blade.php` $\rightarrow$ Formulir login modern dengan dukungan remember me.
   - `resources/views/auth/register.blade.php` $\rightarrow$ Formulir registrasi mandiri untuk user/siswa.

2. **Hak Akses & Otorisasi:**
   - `app/Http/Middleware/CheckRole.php` $\rightarrow$ Pintu gerbang keamanan: memeriksa apakah `auth()->user()->role` cocok dengan parameter rute.
   - `bootstrap/app.php` $\rightarrow$ Lokasi pendaftaran alias middleware `'role' => \App\Http\Middleware\CheckRole::class`.

3. **Dashboard & Layout:**
   - `resources/views/layouts/admin.blade.php` $\rightarrow$ Kerangka utama dashboard (Sidebar responsive, Navbar, SweetAlert notifikasi sukses, gagal, dan error validasi).
   - `resources/views/admin/dashboard.blade.php` $\rightarrow$ Beranda admin (kartu KPI ringkasan dan statistik).
   - `app/Http/Controllers/DashboardController.php` $\rightarrow$ Menghitung data agregat untuk kartu dashboard.

4. **Laporan & PDF:**
   - `app/Http/Controllers/ReportController.php` $\rightarrow$ Memproses filter tanggal (`start_date`, `end_date`) dan render dokumen PDF.
   - `resources/views/reports/index.blade.php` $\rightarrow$ Filter tanggal interaktif dan preview tabel di web.
   - `resources/views/reports/pdf_template.blade.php` $\rightarrow$ Template kop surat resmi, tabel data, dan area tanda tangan penanggung jawab.

---

## 🔌 2. PANDUAN JAGA-JAGA: MODE LAB OFFLINE (TANPA INTERNET)

Meskipun ujianmu direncanakan online, beberapa sekolah sering memutus sambungan internet saat ujian berlangsung untuk mencegah browsing. Berikut langkah antisipasinya:

### Masalah Mode Offline:
Template RapidStrat secara default menggunakan CDN untuk:
1. Tailwind CSS (`cdn.tailwindcss.com`)
2. Font Awesome Icons (`cdnjs.cloudflare.com/...`)
3. SweetAlert2 (`cdn.jsdelivr.net/...`)
4. Google Fonts Plus Jakarta Sans

### Solusi Praktis (Offline-Ready):
Jika internet tiba-tiba mati:
1. **Simpan Asset Lokal Sebelum Ujian:**
   Saat masih ada internet, unduh file CSS/JS tersebut atau salin isi scriptnya ke folder:
   - `public/css/tailwind.css`
   - `public/css/all.min.css` (FontAwesome)
   - `public/js/sweetalert2.all.min.js`
2. **Ganti Link di `resources/views/layouts/admin.blade.php`:**
   ```html
   <!-- Ganti CDN dengan asset lokal -->
   <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
   <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
   <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
   ```
3. **Fallback Cetak Laporan (Tanpa Composer DomPDF):**
   Jika di lab kamu tidak bisa menjalankan `composer require barryvdh/laravel-dompdf`, **jangan panik!**
   Controller laporan RapidStrat sudah dilengkapi sistem **Cetak Langsung Browser (`window.print`)**. Fitur cetak tetap menghasilkan dokumen PDF rapi melalui menu *"Save as PDF"* pada dialog print browser penguji.

---

## 💡 3. CARA MENYESUAIKAN DENGAN SOAL UJIKOM KAMU

Apapun soal Ujikom yang kamu terima nanti, ikuti 4 pilar ini:

### Langkah A: Buat Migration & Model Sesuai Soal
Contoh jika soalnya tentang **Buku** (Perpustakaan) atau **Mobil** (Rental):
```bash
php artisan make:model Mobil -m
```
Di file migration (`database/migrations/..._create_mobils_table.php`):
```php
$table->string('nama_mobil');
$table->string('plat_nomor')->unique();
$table->integer('harga_sewa_per_hari');
$table->enum('status', ['tersedia', 'disewa'])->default('tersedia');
```
Jalankan migrasi:
```bash
php artisan migrate
```

### Langkah B: Daftarkan `$fillable` di Model
Di `app/Models/Mobil.php`:
```php
protected $fillable = ['nama_mobil', 'plat_nomor', 'harga_sewa_per_hari', 'status'];
```

### Langkah C: Pakai Blueprint CRUD dari RapidStrat
1. Buat Controller:
   ```bash
   php artisan make:controller MobilController
   ```
2. Salin logika dari: `app/Http/Controllers/ContohController.php` ke `MobilController.php`. Cukup sesuaikan nama model dan kolom validasinya.
3. Di `routes/web.php`, daftarkan resource:
   ```php
   Route::resource('mobil', MobilController::class);
   ```

---

## 🎤 4. BOCORAN PERTANYAAN PENGUJI & CARA MENJAWABNYA (A+ ANSWER)

### ❓ Pertanyaan 1: "Bagaimana alur autentikasi dan otorisasi multi-role di aplikasi kamu?"
> **Jawaban:**
> *"Alurnya terbagi dua Pak/Bu: **Autentikasi** dan **Otorisasi**.
> 1. Autentikasi ditangani oleh `AuthController` menggunakan `Auth::attempt()`. Setelah email dan password valid, session diregenerasi untuk mencegah session fixation.
> 2. Otorisasi ditangani oleh middleware kustom `CheckRole`. Middleware ini memeriksa kolom `role` pada akun yang login. Jika rolenya `admin/petugas`, mereka diizinkan masuk ke rute `/admin/*`. Jika rolenya adalah pengguna biasa, sistem membatasi akses dan hanya mengizinkan ke dashboard pengguna."*

---

### ❓ Pertanyaan 2: "Mengapa kode program kamu memiliki banyak blok komentar penjelasan?"
> **Jawaban (Nilai Plus):**
> *"Komentar tersebut saya susun mengikuti standar dokumentasi industri (*clean documentation*), Pak/Bu. Tujuannya adalah memastikan prinsip *readability* dan *maintainability* terpenuhi, sehingga pengembang lain (maupun penguji) dapat memahami tanggung jawab setiap method, alasan di balik validasi input, dan pencegahan celah keamanan seperti CSRF dan Mass Assignment."*

---

### ❓ Pertanyaan 3: "Bagaimana cara kerja fitur Cetak Laporan PDF?"
> **Jawaban:**
> *"Pengguna memilih rentang tanggal pada halaman `ReportController::index`. Controller melakukan filter data menggunakan Eloquent `whereBetween('created_at', [$startDate, $endDate])`. Setelah itu data di-passing ke view `reports.pdf_template` yang dikonversi menjadi dokumen PDF siap unduh oleh library DomPDF. Selain itu, saya juga menyediakan opsi cetak browser langsung dengan format print-ready CSS."*

---

### ❓ Pertanyaan 4: "Apa yang kamu lakukan untuk mencegah celah keamanan (Security)?"
> **Jawaban:**
> *"Ada 4 lapisan keamanan utama:
> 1. **SQL Injection**: Dicegah dengan selalu menggunakan Eloquent ORM / Parameterized Queries.
> 2. **CSRF Attack**: Seluruh form POST, PUT, dan DELETE dilindungi oleh direktif `@csrf`.
> 3. **Mass Assignment**: Kolom database dilindungi menggunakan properti `$fillable` di setiap model Eloquent.
> 4. **Password Hashing**: Kata sandi dienkripsi menggunakan algoritma Bcrypt (`Hash::make`)."*

---

## 🚀 PERINTAH CEPAT YANG SERING DIPAKAI:
- Menjalankan server lokal: `php artisan serve`
- Reset database & seeder ulang dari awal: `php artisan migrate:fresh --seed`
- Bersihkan cache konfigurasi & rute: `php artisan optimize:clear`
- Cek daftar rute aktif: `php artisan route:list`
