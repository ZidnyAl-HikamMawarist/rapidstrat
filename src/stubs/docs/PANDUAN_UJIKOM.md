# 📘 PANDUAN ARSITEKTUR, RUMUS PERHITUNGAN, RELASI ELOQUENT & CONTEKAN ASESOR BNSP (RAPIDSTRAT)

Dokumen ini adalah **peta navigasi, panduan teknis, dan contekan resmi** untuk membantumu memahami seluruh alur kode, menerapkan rumus bisnis perhitungan, menghubungkan relasi database, mempersiapkan kondisi darurat offline, dan menjawab semua pertanyaan penguji/asesor saat Ujikom (UKK RPL / LSP BNSP).

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

## 🧮 2. KUMPULAN CONTEKAN RUMUS PERHITUNGAN BISNIS (BUSINESS LOGIC)

Dalam ujian, asesor sering menilai kemampuan kalkulasi logika. Letakkan rumus-rumus ini di Controller pada method `store()` atau `update()` sebelum data disimpan:

### A. Kasus Kasir / Penjualan (Subtotal, Diskon, Pajak & Kembalian)
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'barang_id'  => 'required|exists:barangs,id',
        'jumlah'     => 'required|integer|min:1',
        'uang_bayar' => 'required|numeric|min:0',
    ]);

    $barang = Barang::findOrFail($validated['barang_id']);

    // 1. Cek kecukupan stok barang
    if ($barang->stok < $validated['jumlah']) {
        return back()->with('error', 'Stok barang tidak mencukupi! Sisa stok: ' . $barang->stok);
    }

    // 2. Rumus Subtotal
    $subtotal = $validated['jumlah'] * $barang->harga;

    // 3. Rumus Diskon (Contoh: Diskon 10% jika belanja >= Rp 100.000)
    $diskon = ($subtotal >= 100000) ? ($subtotal * 0.10) : 0;

    // 4. Rumus Pajak PPN 11% (dari nilai setelah diskon)
    $pajak = ($subtotal - $diskon) * 0.11;

    // 5. Total Akhir & Kembalian
    $totalBayar = ($subtotal - $diskon) + $pajak;
    $kembalian  = $validated['uang_bayar'] - $totalBayar;

    // 6. Validasi uang pembayaran
    if ($kembalian < 0) {
        return back()->with('error', 'Uang pembayaran kurang sebesar: Rp ' . number_format(abs($kembalian), 0, ',', '.'));
    }

    // 7. Simpan transaksi
    Transaksi::create([
        'user_id'     => auth()->id(),
        'barang_id'   => $barang->id,
        'jumlah'      => $validated['jumlah'],
        'subtotal'    => $subtotal,
        'diskon'      => $diskon,
        'pajak'       => $pajak,
        'total_bayar' => $totalBayar,
        'uang_bayar'  => $validated['uang_bayar'],
        'kembalian'   => $kembalian,
    ]);

    // 8. Kurangi stok barang secara atomik
    $barang->decrement('stok', $validated['jumlah']);

    return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
}
```

---

### B. Kasus Perpustakaan (Hitung Keterlambatan & Denda)
Gunakan library bawaan Laravel: **`Carbon\Carbon`** untuk memproses selisih tanggal:

```php
use Carbon\Carbon;

public function prosesPengembalian(Request $request, $id)
{
    $pinjam = Peminjaman::findOrFail($id);

    $tglJatuhTempo = Carbon::parse($pinjam->tanggal_jatuh_tempo); // misal: 7 hari setelah pinjam
    $tglKembali    = Carbon::now(); // Waktu saat buku dikembalikan hari ini

    // 1. Hitung selisih hari jika tanggal kembali melebihi jatuh tempo
    $hariTerlambat = $tglKembali->greaterThan($tglJatuhTempo)
        ? $tglKembali->diffInDays($tglJatuhTempo)
        : 0;

    // 2. Rumus Denda: Rp 2.000 per hari keterlambatan
    $tarifDendaPerHari = 2000;
    $totalDenda = $hariTerlambat * $tarifDendaPerHari;

    // 3. Update status peminjaman
    $pinjam->update([
        'tanggal_pengembalian' => $tglKembali,
        'hari_terlambat'       => $hariTerlambat,
        'denda'                => $totalDenda,
        'status'               => 'kembali',
    ]);

    // 4. Kembalikan stok buku (+1)
    $pinjam->buku->increment('stok', 1);

    return back()->with('success', "Buku berhasil dikembalikan. Denda: Rp " . number_format($totalDenda, 0, ',', '.'));
}
```

---

### C. Kasus Rental Mobil (Lama Sewa x Tarif Harian)
```php
use Carbon\Carbon;

public function storeRental(Request $request)
{
    $validated = $request->validate([
        'mobil_id'    => 'required|exists:mobils,id',
        'tgl_mulai'   => 'required|date',
        'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
    ]);

    $tglMulai   = Carbon::parse($validated['tgl_mulai']);
    $tglSelesai = Carbon::parse($validated['tgl_selesai']);

    // 1. Hitung total hari sewa (minimal 1 hari)
    $lamaSewaHari = max(1, $tglMulai->diffInDays($tglSelesai));

    // 2. Ambil tarif sewa per hari mobil
    $mobil = Mobil::findOrFail($validated['mobil_id']);

    // 3. Rumus Total Biaya
    $totalBiaya = $lamaSewaHari * $mobil->harga_sewa_per_hari;

    Rental::create([
        'user_id'     => auth()->id(),
        'mobil_id'    => $mobil->id,
        'tgl_mulai'   => $tglMulai,
        'tgl_selesai' => $tglSelesai,
        'total_hari'  => $lamaSewaHari,
        'total_biaya' => $totalBiaya,
        'status'      => 'disewa',
    ]);

    // Update status mobil menjadi disewa
    $mobil->update(['status' => 'disewa']);

    return redirect()->route('rental.index')->with('success', 'Rental mobil berhasil didaftarkan!');
}
```

---

### D. Realtime Calculation di Form Blade (JavaScript Interaktif)
Tambahkan script kecil ini di bawah form Blade agar nilai total langsung berubah saat user mengetik angka:
```html
<script>
    const inputJumlah = document.getElementById('jumlah');
    const inputHarga  = document.getElementById('harga');
    const inputTotal  = document.getElementById('total');

    function hitungSubtotal() {
        const qty   = parseFloat(inputJumlah.value) || 0;
        const harga = parseFloat(inputHarga.value) || 0;
        inputTotal.value = 'Rp ' + (qty * harga).toLocaleString('id-ID');
    }

    inputJumlah.addEventListener('input', hitungSubtotal);
</script>
```

---

## 🔗 3. PANDUAN RELASI DATABASE ELOQUENT (ONE-TO-MANY)

Asesor BNSP mewajibkan integrasi relasi antar tabel database (*Foreign Key*):

### 1. Relasi Kategori $\leftrightarrow$ Buku / Barang (One to Many)
* **Di Model Induk (`app/Models/Category.php`):**
  ```php
  // 1 Kategori memiliki BANYAK Buku
  public function books()
  {
      return $this->hasMany(Book::class, 'category_id');
  }
  ```
* **Di Model Anak (`app/Models/Book.php`):**
  ```php
  // 1 Buku MILIK 1 Kategori
  public function category()
  {
      return $this->belongsTo(Category::class, 'category_id');
  }
  ```

### 2. Relasi User $\leftrightarrow$ Transaksi / Peminjaman
* **Di Model Transaksi (`app/Models/Loan.php` atau `Transaksi.php`):**
  ```php
  public function user()
  {
      return $this->belongsTo(User::class, 'user_id');
  }

  public function book()
  {
      return $this->belongsTo(Book::class, 'book_id');
  }
  ```

### 3. Cara Menampilkan Relasi di View Blade & Mencegah N+1 Problem:
* **Di Controller (Gunakan `with` untuk Eager Loading):**
  ```php
  // Eager Loading: Mengambil data buku sekaligus relasi kategorinya dalam 1 query hemat
  $items = Book::with('category')->latest()->paginate(10);
  ```
* **Di Tabel Blade (`index.blade.php`):**
  ```blade
  <!-- Tampilkan nama kategori dari relasi -->
  <td>{{ $item->category->name ?? 'Tanpa Kategori' }}</td>
  ```

---

## 🏆 4. PETA JAWABAN 8 UNIT KOMPETENSI SKKNI BNSP (LSP TEMATIK NUSANTARA)

Gunakan contekan ini saat asesor menguji unit-unit kompetensi Anda:

| Kode Unit | Judul Unit Kompetensi | Cara Menjawab & Membuktikan ke Asesor |
| :--- | :--- | :--- |
| **`J.620100.004.02`** | **Menggunakan struktur data** | *"Saya menggunakan tipe data primitif (string, integer, boolean) dan struktur data kompleks berupa **Associative Array** untuk validasi `$request->validate()` serta **Collection Eloquent** untuk menampung dan memanipulasi data tabel dari database MySQL."* |
| **`J.620100.005.02`** | **Mengimplementasikan user interface** | *"UI dibangun menggunakan arsitektur Blade Template Inheritance (`@extends`, `@section`, `@yield`) dengan styling modern Tailwind CSS, tata letak responsif untuk desktop dan mobile, serta umpan balik modal interaktif SweetAlert2."* |
| **`J.620100.011.01`** | **Melakukan instalasi software tools pemrograman** | *"Saya mengonfigurasi environment pengembangan lokal menggunakan Laragon (Apache, MySQL, PHP 8.2+), Git Version Control, Composer Package Manager, dan code editor Visual Studio Code."* |
| **`J.620100.016.01`** | **Menulis kode sesuai guidelines & best practices** | *"Kode saya menerapkan standar arsitektur **MVC**, standar penamaan PSR-4/PSR-12, RESTful routing, serta dokumentasi blok komentar PHPDoc di setiap method untuk menjamin prinsip Clean Code dan kemudahan pemeliharaan."* |
| **`J.620100.017.02`** | **Mengimplementasikan pemrograman terstruktur** | *"Saya menerapkan struktur kontrol percabangan logika (`if/else`, `match`, `switch`) pada filter autentikasi peran pengguna, perulangan tabel data `@forelse`, serta pemisahan logika bisnis ke method modular terpisah."* |
| **`J.620100.019.02`** | **Menggunakan library atau komponen pre-existing** | *"Saya memanfaatkan package Composer pihak ketiga seperti `barryvdh/laravel-dompdf` untuk cetak laporan, SweetAlert2 untuk modal dialog, dan **bahkan saya merilis package library composer sendiri bernama `zidnyal-hikammawarist/rapidstrat` di Packagist** untuk standardisasi scaffolding sistem."* |
| **`J.620100.023.02`** | **Membuat dokumen kode program** | *"Dokumentasi sistem telah disusun secara terperinci pada file `README.md`, panduan instalasi package di Packagist, dokumen teknis `PANDUAN_UJIKOM.md`, serta komentar inline yang mendeskripsikan tujuan setiap blok kode."* |
| **`J.620100.025.02`** | **Melakukan debugging** | *"Saya melakukan debugging menggunakan error trace Laravel Ignition, query debugging, serta penanganan exception basis data seperti validasi Mass Assignment `$fillable` dan penanganan nilai default kolom MySQL."* |

---

## 🔌 5. PANDUAN JAGA-JAGA: MODE LAB OFFLINE (TANPA INTERNET)

Jika koneksi internet lab komputer sekolah dimatikan oleh penguji saat ujian:
1. **Simpan Asset Lokal Sebelum Ujian:**
   Unduh file script ke direktori proyek lokal:
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
   Jika tidak bisa install DomPDF, fitur cetak RapidStrat otomatis mengalihkan ke tampilan **Cetak Langsung Browser (`window.print`)**. Fitur cetak tetap menghasilkan dokumen PDF A4 resmi via dialog print browser.

---

## 🎤 6. BOCORAN PERTANYAAN PENGUJI & CARA MENJAWABNYA (A+ ANSWER)

### ❓ Pertanyaan 1: "Bagaimana alur autentikasi dan otorisasi multi-role di aplikasi kamu?"
> **Jawaban:**
> *"Alurnya terbagi dua: **Autentikasi** dan **Otorisasi**.
> 1. Autentikasi ditangani oleh `AuthController` menggunakan `Auth::attempt()`. Setelah email dan password valid, session diregenerasi untuk mencegah session fixation.
> 2. Otorisasi ditangani oleh middleware kustom `CheckRole`. Middleware ini memeriksa kolom `role` pada akun yang login. Jika rolenya `admin/petugas`, mereka diizinkan masuk ke rute `/admin/*`. Jika rolenya adalah pengguna biasa, sistem membatasi akses dan hanya mengizinkan ke dashboard pengguna."*

---

### ❓ Pertanyaan 2: "Apa yang kamu lakukan untuk mencegah celah keamanan (Security)?"
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
