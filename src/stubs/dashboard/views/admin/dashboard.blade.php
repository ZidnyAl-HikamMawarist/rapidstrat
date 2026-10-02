@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Ringkasan Sistem')
@section('page-subtitle', 'Selamat datang di panel kontrol utama')

@section('content')
<div class="space-y-6">

    <!-- Hero Greeting Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900/40 via-slate-900 to-slate-900 border border-indigo-500/20 p-6 md:p-8">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mb-3">
                    <i class="fa-solid fa-sparkles"></i> Siap untuk Ujikom
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    Halo, {{ $user->name ?? 'Administrator' }} 👋
                </h1>
                <p class="text-sm text-slate-400 mt-1 max-w-xl">
                    Anda masuk sebagai <strong class="text-indigo-400 uppercase font-semibold">{{ $user->role ?? 'admin' }}</strong>. Semua modul sistem siap digunakan dan dikelola.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if(Route::has('reports.index'))
                <a href="{{ route('reports.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition border border-slate-700 flex items-center gap-2">
                    <i class="fa-solid fa-print text-indigo-400"></i> Cetak Laporan
                </a>
                @endif
                @if(Route::has('master.create'))
                <a href="{{ route('master.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Data
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 4 Metric / KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['total_users'] ?? 0 }}</h3>
                <span class="text-[11px] text-emerald-400 flex items-center gap-1 mt-1 font-medium">
                    <i class="fa-solid fa-arrow-trend-up"></i> Terdaftar aktif
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Jumlah Petugas</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['total_petugas'] ?? 0 }}</h3>
                <span class="text-[11px] text-slate-400 flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-user-shield"></i> Staf operator
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-id-badge"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['total_transaksi'] ?? 0 }}</h3>
                <span class="text-[11px] text-indigo-400 flex items-center gap-1 mt-1 font-medium">
                    <i class="fa-solid fa-clock-rotate-left"></i> Keseluruhan
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-violet-500/10 border border-violet-500/20 text-violet-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Status Menunggu</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['total_pending'] ?? 0 }}</h3>
                <span class="text-[11px] text-amber-400 flex items-center gap-1 mt-1 font-medium">
                    <i class="fa-solid fa-triangle-exclamation"></i> Perlu verifikasi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
    </div>

    <!-- Recent Activity / Example Table -->
    <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 overflow-hidden">
        <div class="p-5 border-b border-slate-800/80 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-white">Aktivitas / Transaksi Terkini</h3>
                <p class="text-xs text-slate-400 mt-0.5">Catatan riwayat interaksi terbaru di sistem</p>
            </div>
            <span class="text-xs font-medium text-indigo-400 hover:text-indigo-300 cursor-pointer">
                Lihat Semua <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800/80">
                    <tr>
                        <th class="py-3 px-5">ID</th>
                        <th class="py-3 px-5">Pengguna / Pemohon</th>
                        <th class="py-3 px-5">Keterangan Aktivitas</th>
                        <th class="py-3 px-5">Tanggal</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="py-3.5 px-5 font-mono text-slate-500">#TRX-001</td>
                        <td class="py-3.5 px-5 font-medium text-white">Budi Santoso</td>
                        <td class="py-3.5 px-5">Pengajuan baru / Transaksi utama</td>
                        <td class="py-3.5 px-5 text-slate-400">{{ date('d M Y, H:i') }}</td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Selesai
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <button class="text-slate-400 hover:text-indigo-400 transition p-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="py-3.5 px-5 font-mono text-slate-500">#TRX-002</td>
                        <td class="py-3.5 px-5 font-medium text-white">Siti Nurhaliza</td>
                        <td class="py-3.5 px-5">Verifikasi data berkas</td>
                        <td class="py-3.5 px-5 text-slate-400">{{ date('d M Y, H:i', strtotime('-2 hours')) }}</td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                Pending
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <button class="text-slate-400 hover:text-indigo-400 transition p-1">
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
