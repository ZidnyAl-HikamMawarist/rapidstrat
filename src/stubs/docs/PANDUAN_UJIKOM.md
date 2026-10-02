# 📘 PANDUAN ARSITEKTUR & CONTEKAN JAWABAN PENGUJI (RAPIDSTRAT)

Dokumen ini adalah **peta navigasi dan contekan resmi** untuk membantumu memahami seluruh alur kode dan menjawab semua pertanyaan penguji saat Ujikom (UKK RPL).

---

## 🗺️ 1. PETA FILE & KONEKSI ARSITEKTUR

Aplikasi ini menggunakan pola arsitektur **MVC (Model - View - Controller)** dengan pemisahan peran (*Multi-Role*).

```text
[ Browser / Pengguna ]
        │
        ▼ (HTTP Request)
[ routes/web.php ]  ────────►  [ CheckRole Middleware ] (Cek apakah role sesuai)
        │
        ▼ (Jika lolos)
[ Controller ]  ◄───────────►  [ Model (Eloquent) ] ◄──► [ Database MySQL ]
        │
        ▼ (Mengirim Data)
[ View (Blade + Tailwind) ]
```

### Daftar File Utama & Fungsinya:
1. **Autentikasi & Login:**
   - `app/Http/Controllers/AuthController.php` $\rightarrow$ Mengatur validasi login, register, dan pengalihan (redirect) sesuai role.
   - `resources/views/auth/login.blade.php` $\rightarrow$ Tampilan halaman login pengguna.
   - `resources/views/auth/register.blade.php` $\rightarrow$ Tampilan halaman registrasi pengguna.

2. **Hak Akses & Otorisasi:**
   - `app/Http/Middleware/CheckRole.php` $\rightarrow$ Satpam gerbang: memeriksa apakah `auth()->user()->role` cocok dengan parameter route.
   - `bootstrap/app.php` $\rightarrow$ Tempat mendaftarkan alias middleware `'role' => \App\Http\Middleware\CheckRole::class`.

3. **Dashboard & Layout:**
   - `resources/views/layouts/admin.blade.php` $\rightarrow$ Kerangka utama dashboard (Sidebar, Navbar, SweetAlert notifikasi).
   - `resources/views/admin/dashboard.blade.php` $\rightarrow$ Halaman depan admin (kartu statistik, aktivitas terkini).
   - `app/Http/Controllers/DashboardController.php` $\rightarrow$ Menghitung data ringkasan untuk kartu dashboard.

4. **Laporan & PDF:**
   - `app/Http/Controllers/ReportController.php` $\rightarrow$ Memproses filter tanggal (`start_date`, `end_date`) dan render PDF.
   - `resources/views/reports/index.blade.php` $\rightarrow$ Tampilan filter tanggal dan preview tabel di web.
   - `resources/views/reports/pdf_template.blade.php` $\rightarrow$ Desain kertas kop resmi dan tabel tanda tangan untuk PDF.

---

## 💡 2. CARA MENYESUAIKAN DENGAN SOAL UJIKOM KAMU

Apapun soal Ujikom yang kamu terima nanti, ikuti langkah mudah ini:

### Langkah A: Bikin Migration & Model untuk Soal Tersebut
Contoh jika soalnya tentang **Buku** (Perpustakaan) atau **Barang** (Kasir):
```bash
php artisan make:model Barang -m
```
Di migration (`database/migrations/..._create_barangs_table.php`), tambahkan kolom:
```php
$table->string('nama_barang');
$table->integer('harga');
$table->integer('stok');
$table->text('deskripsi')->nullable();
```
Jalankan: `php artisan migrate`

### Langkah B: Gunakan Stubs CRUD dari RapidStrat
1. Buat controller:
   ```bash
   php artisan make:controller BarangController
   ```
2. Salin logika dari file stubs: `app/Http/Controllers/ContohController.php` ke `BarangController.php`. Cukup sesuaikan nama kolomnya (`nama_barang`, `harga`, `stok`).
3. Buat folder view: `resources/views/barang/index.blade.php` dan `form.blade.php` dengan menyalin dari `resources/views/master/`.
4. Daftarkan di `routes/web.php`:
   ```php
   Route::resource('barang', BarangController::class);
   ```

---

## 🎤 3. BOCORAN PERTANYAAN PENGUJI & CARA MENJAWABNYA (A+ ANSWER)

### ❓ Pertanyaan 1: "Bagaimana alur kerja autentikasi dan multi-role di aplikasi kamu?"
> **Jawaban:**
> *"Alurnya begini Pak/Bu: Ketika pengguna mengisi email dan password di form login, data dikirim ke method `login()` di `AuthController`. Sistem memverifikasi kredensial menggunakan `Auth::attempt()`. Jika berhasil, sistem memeriksa nilai kolom `role` pada akun tersebut. Jika rolenya adalah `admin` atau `petugas`, user diarahkan ke `/admin/dashboard`. Jika rolenya `user/siswa`, diarahkan ke `/dashboard` pengguna. Selain itu, setiap route internal diamankan oleh middleware `CheckRole` yang memeriksa role aktif di setiap perpindahan halaman."*

---

### ❓ Pertanyaan 2: "Apa fungsi Middleware dan bagaimana kamu menerapkannya?"
> **Jawaban:**
> *"Middleware berfungsi sebagai lapisan penyaring (filter) HTTP request sebelum mencapai controller, Pak/Bu. Di aplikasi ini, saya membuat middleware `CheckRole`. Cara kerjanya: middleware memeriksa apakah pengguna sudah login, lalu mencocokkan `auth()->user()->role` dengan daftar role yang diizinkan pada route tersebut (misalnya `middleware('role:admin')`). Jika pengguna tidak punya akses, sistem langsung menghentikan request dengan kode error 403 Forbidden."*

---

### ❓ Pertanyaan 3: "Bagaimana cara kerja fitur Cetak Laporan PDF?"
> **Jawaban:**
> *"Fitur laporan berada di `ReportController`. Pengguna memilih rentang `start_date` dan `end_date` pada form filter. Controller melakukan query ke database menggunakan method Eloquent `whereBetween('created_at', [$startDate, $endDate])`. Hasil datanya kemudian dimasukkan ke view `reports.pdf_template`, lalu diproses oleh library `Barryvdh\DomPDF` untuk di-render menjadi dokumen PDF berukuran A4 yang siap diunduh dan dicetak."*

---

### ❓ Pertanyaan 4: "Bagaimana hubungan (relasi) antar tabel di database kamu?"
*(Sesuaikan dengan studi kasus soal yang kamu dapat)*
> **Jawaban (Contoh Perpustakaan / Kasir):**
> *"Relasi yang saya bangun adalah **One-to-Many**. Contohnya, satu Kategori bisa memiliki banyak Buku (`hasMany`), dan setiap Buku dimiliki oleh satu Kategori (`belongsTo`). Begitu juga pada transaksi: satu User/Pelanggan dapat memiliki banyak Transaksi Peminjaman/Penjualan."*
> 
> **Kode di Model:**
> ```php
> // Di Model Transaksi:
> public function user() {
>     return $this->belongsTo(User::class);
> }
> ```

---

### ❓ Pertanyaan 5: "Kenapa kamu menggunakan library RapidStrat ini?"
> **Jawaban:**
> *"RapidStrat adalah open-source starter scaffolding kit yang saya siapkan untuk standarisasi arsitektur proyek, Pak/Bu. Tujuannya agar waktu pengerjaan tidak habis untuk hal-hal repetitif seperti layout CSS dan autentikasi dasar, sehingga saya bisa memusatkan waktu dan fokus pada **penyelesaian logika bisnis, validasi data, dan relasi transaksi** sesuai soal yang diberikan."*

---

### 🚀 Perintah Cepat yang Sering Dipakai:
- Jalankan server: `php artisan serve`
- Reset database & seeder ulang: `php artisan migrate:fresh --seed`
- Bersihkan cache jika ada error aneh: `php artisan optimize:clear`
