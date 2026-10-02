<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'RapidStrat') }}</title>
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
    @stack('styles')
</head>
<body class="h-full bg-slate-950 text-slate-100 flex overflow-hidden">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800/80 flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        <div>
            <!-- Brand Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800/80">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-600/30">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight text-white block leading-tight">{{ config('app.name', 'RapidStrat') }}</span>
                        <span class="text-[10px] text-indigo-400 font-semibold uppercase tracking-wider block">Admin Panel</span>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- User Info Pill -->
            <div class="p-4 mx-3 mt-4 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ auth()->user()->role === 'admin' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                        <i class="fa-solid fa-circle text-[6px] mr-1"></i> {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1 mt-3">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Utama</p>

                <!-- Dashboard Link -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider pt-4 mb-2">Manajemen & Transaksi</p>

                <!-- Menu Master Data (Contoh: Buku / Barang / Menu) -->
                @if(Route::has('master.index'))
                <a href="{{ route('master.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('master.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                    <span>Master Data</span>
                </a>
                @endif

                <!-- Menu Transaksi (Contoh: Peminjaman / Penjualan) -->
                @if(Route::has('transaksi.index'))
                <a href="{{ route('transaksi.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('transaksi.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-receipt w-5 text-center"></i>
                    <span>Transaksi</span>
                </a>
                @endif

                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider pt-4 mb-2">Laporan</p>

                <!-- Menu Laporan -->
                @if(Route::has('reports.index'))
                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                    <i class="fa-solid fa-file-pdf w-5 text-center"></i>
                    <span>Cetak Laporan</span>
                </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer / Logout -->
        <div class="p-3 border-t border-slate-800/80">
            <button onclick="confirmLogout()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-rose-400 hover:bg-rose-500/10 transition">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                <span>Keluar Akun</span>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- TOP NAVBAR -->
        <header class="h-16 bg-slate-900/60 backdrop-blur-md border-b border-slate-800/80 flex items-center justify-between px-4 md:px-8">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white p-2 rounded-lg hover:bg-slate-800">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-sm font-semibold text-white">@yield('page-title', 'Overview')</h2>
                    <p class="text-[11px] text-slate-400">@yield('page-subtitle', 'Panel Kontrol Sistem')</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif
                </span>

                <div class="h-6 w-px bg-slate-800 hidden md:block"></div>

                <!-- User Quick Info -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-300 font-medium hidden sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <button onclick="confirmLogout()" title="Logout" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800/80 rounded-lg transition">
                        <i class="fa-solid fa-power-off text-sm"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-950">
            @yield('content')
        </main>
    </div>

    <!-- SweetAlert2 Notification Handler -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar?',
                text: "Sesi Anda akan diakhiri.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#f8fafc',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#4f46e5',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#e11d48'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
