@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Ringkasan Sistem')
@section('page-subtitle', 'Selamat datang di panel kontrol utama')

@section('content')
<div class="space-y-6">

    <!-- Hero Greeting Card (Light Mode) -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-50 via-white to-violet-50 border border-indigo-100/80 p-6 md:p-8 shadow-sm">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-indigo-500/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 mb-3">
                    <i class="fa-solid fa-sparkles text-indigo-500"></i> Siap untuk Ujikom
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Halo, {{ $user->name ?? 'Administrator' }} 👋
                </h1>
                <p class="text-sm text-slate-600 mt-1 max-w-xl font-medium">
                    Anda masuk sebagai <strong class="text-indigo-600 uppercase font-bold">{{ $user->role ?? 'admin' }}</strong>. Semua modul sistem siap digunakan dan dikelola.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if(Route::has('reports.index'))
                <a href="{{ route('reports.index') }}" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition border border-slate-200 shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-print text-indigo-600"></i> Cetak Laporan
                </a>
                @endif
                @if(Route::has('master.create'))
                <a href="{{ route('master.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition shadow-md shadow-indigo-600/25 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Data
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 4 Metric / KPI Cards (Light Mode) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total_users'] ?? 0 }}</h3>
                <span class="text-[11px] text-emerald-600 flex items-center gap-1 mt-1 font-semibold">
                    <i class="fa-solid fa-arrow-trend-up"></i> Terdaftar aktif
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Petugas</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total_petugas'] ?? 0 }}</h3>
                <span class="text-[11px] text-slate-500 flex items-center gap-1 mt-1 font-medium">
                    <i class="fa-solid fa-user-shield text-slate-400"></i> Staf operator
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-id-badge"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total_transaksi'] ?? 0 }}</h3>
                <span class="text-[11px] text-indigo-600 flex items-center gap-1 mt-1 font-semibold">
                    <i class="fa-solid fa-clock-rotate-left"></i> Keseluruhan
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Menunggu</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total_pending'] ?? 0 }}</h3>
                <span class="text-[11px] text-amber-600 flex items-center gap-1 mt-1 font-semibold">
                    <i class="fa-solid fa-triangle-exclamation"></i> Perlu verifikasi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table (Light Mode) -->
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Aktivitas / Transaksi Terkini</h3>
                <p class="text-xs text-slate-500 mt-0.5">Catatan riwayat interaksi terbaru di sistem</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 cursor-pointer flex items-center gap-1">
                Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200 font-bold">
                    <tr>
                        <th class="py-3.5 px-5">ID</th>
                        <th class="py-3.5 px-5">Pengguna / Pemohon</th>
                        <th class="py-3.5 px-5">Keterangan Aktivitas</th>
                        <th class="py-3.5 px-5">Tanggal</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-5 font-mono text-slate-400 font-medium">#TRX-001</td>
                        <td class="py-3.5 px-5 font-semibold text-slate-900">Budi Santoso</td>
                        <td class="py-3.5 px-5 text-slate-600">Pengajuan baru / Transaksi utama</td>
                        <td class="py-3.5 px-5 text-slate-500">{{ date('d M Y, H:i') }}</td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Selesai
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <button class="text-slate-400 hover:text-indigo-600 transition p-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-5 font-mono text-slate-400 font-medium">#TRX-002</td>
                        <td class="py-3.5 px-5 font-semibold text-slate-900">Siti Nurhaliza</td>
                        <td class="py-3.5 px-5 text-slate-600">Verifikasi data berkas</td>
                        <td class="py-3.5 px-5 text-slate-500">{{ date('d M Y, H:i', strtotime('-2 hours')) }}</td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Pending
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <button class="text-slate-400 hover:text-indigo-600 transition p-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
