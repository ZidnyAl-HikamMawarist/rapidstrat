<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>
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
<body class="h-full bg-slate-50 text-slate-800 flex overflow-hidden">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/90 shadow-sm flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        <div>
            <!-- Brand Logo: Hanya Admin Panel (Tanpa kata Laravel) -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-600/20">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight text-slate-900 block leading-tight">Admin Panel</span>
                        <span class="text-[10px] text-indigo-600 font-semibold uppercase tracking-wider block">Sistem Manajemen</span>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-slate-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Utama</p>

                <!-- Dashboard Link -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider pt-5 mb-2">Manajemen & Transaksi</p>

                <!-- Menu Master Data -->
                @if(Route::has('master.index'))
                <a href="{{ route('master.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('master.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm {{ request()->routeIs('master.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Master Data</span>
                </a>
                @endif

                <!-- Menu Transaksi -->
                @if(Route::has('transaksi.index'))
                <a href="{{ route('transaksi.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('transaksi.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <i class="fa-solid fa-receipt w-5 text-center text-sm {{ request()->routeIs('transaksi.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Transaksi</span>
                </a>
                @endif

                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider pt-5 mb-2">Laporan</p>

                <!-- Menu Laporan -->
                @if(Route::has('reports.index'))
                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                    <i class="fa-solid fa-file-pdf w-5 text-center text-sm {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Cetak Laporan</span>
                </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Bottom Footer -->
        <div class="p-4 border-t border-slate-100">
            <button onclick="confirmLogout()" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition border border-rose-100">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                <span>Keluar Akun</span>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
        <!-- TOP NAVBAR -->
        <header class="h-16 bg-white border-b border-slate-200/80 shadow-sm flex items-center justify-between px-4 md:px-8 z-10">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden text-slate-500 hover:text-slate-800 p-2 rounded-lg hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">@yield('page-title', 'Overview')</h2>
                    <p class="text-[11px] text-slate-500 font-medium">@yield('page-subtitle', 'Panel Kontrol Sistem')</p>
                </div>
            </div>

            <!-- BAGIAN POJOK KANAN ATAS: Status Sistem + User Profile Pill Lengkap -->
            <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <span class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif
                </span>

                <!-- Profil Pengguna (Dipindah ke Pojok Kanan Atas) -->
                <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ (auth()->user()->role ?? 'admin') === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ (auth()->user()->role ?? 'admin') === 'admin' ? 'bg-indigo-500' : 'bg-emerald-500' }} mr-1"></span>
                                {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Power / Logout Cepat -->
                    <button onclick="confirmLogout()" title="Keluar Akun"
                            class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition border border-transparent hover:border-rose-100">
                        <i class="fa-solid fa-power-off text-sm"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-50">
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
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                color: '#1e293b',
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
                background: '#ffffff',
                color: '#1e293b',
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
                background: '#ffffff',
                color: '#1e293b',
                confirmButtonColor: '#e11d48'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
