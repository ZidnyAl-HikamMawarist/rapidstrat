<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pengguna - {{ config('app.name', 'RapidStrat') }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="h-16 border-b border-slate-800 bg-slate-900/80 backdrop-blur-md px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold">
                <i class="fa-solid fa-layer-group text-xs"></i>
            </div>
            <span class="font-bold text-white">{{ config('app.name', 'RapidStrat') }}</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-slate-400">Halo, <strong class="text-slate-200">{{ auth()->user()->name }}</strong></span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold hover:bg-rose-500 hover:text-white transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-5xl w-full mx-auto p-6 md:p-10 space-y-6">
        <div class="p-8 rounded-2xl bg-slate-900/70 border border-slate-800 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-2xl mb-4">
                <i class="fa-solid fa-user-circle"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Selamat Datang di Portal Pengguna</h1>
            <p class="text-sm text-slate-400 mt-2 max-w-md mx-auto">
                Akun Anda terdaftar dengan hak akses <span class="text-indigo-400 font-semibold">{{ ucfirst(auth()->user()->role) }}</span>.
            </p>
        </div>

        <div class="p-6 rounded-2xl bg-slate-900/70 border border-slate-800">
            <h2 class="text-base font-bold text-white mb-2">Riwayat & Pengajuan Saya</h2>
            <p class="text-xs text-slate-400 mb-4">Halaman ini digunakan untuk melihat data peminjaman, permohonan, atau transaksi milik akun Anda.</p>
            <div class="p-8 text-center border border-dashed border-slate-800 rounded-xl text-slate-500 text-xs">
                <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-600"></i>
                Belum ada transaksi aktif saat ini.
            </div>
        </div>
    </main>
</body>
</html>
