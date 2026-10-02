<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ config('app.name', 'RapidStrat') }}</title>
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
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white relative overflow-hidden">
    <!-- Background Glow Effects -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-lg shadow-indigo-500/30 mb-4 ring-1 ring-white/20">
                <i class="fa-solid fa-layer-group text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">{{ config('app.name', 'RapidStrat') }}</h1>
            <p class="text-sm text-slate-400 mt-1">Silakan masuk dengan akun terdaftar Anda</p>
        </div>

        <!-- Card Container -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-7 shadow-2xl shadow-black/50">
            <!-- Alert Session / Error -->
            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border @error('email') border-rose-500/60 ring-1 ring-rose-500/40 @else border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror rounded-xl text-slate-100 placeholder-slate-500 text-sm transition outline-none">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-400 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-950/60 border @error('password') border-rose-500/60 ring-1 ring-rose-500/40 @else border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror rounded-xl text-slate-100 placeholder-slate-500 text-sm transition outline-none">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300">
                            <i id="eye-icon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-400 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-400 hover:text-slate-200">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-indigo-600 focus:ring-indigo-500/50">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-2.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Masuk ke Akun</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            @if(Route::has('register'))
                <div class="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-400">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-400 hover:text-indigo-300 ml-1 transition">Daftar sekarang</a>
                </div>
            @endif
        </div>

        <!-- Demo Accounts Box (Sangat berguna saat demo ke penguji!) -->
        <div class="mt-6 p-4 rounded-xl bg-slate-900/50 border border-slate-800/60 text-xs text-slate-400">
            <div class="flex items-center gap-2 font-semibold text-slate-300 mb-2">
                <i class="fa-solid fa-key text-indigo-400"></i> Akun Pengujian (Demo):
            </div>
            <div class="grid grid-cols-2 gap-2 text-[11px] font-mono">
                <div class="bg-slate-950/60 p-2 rounded border border-slate-800/80">
                    <span class="text-indigo-400 block font-sans font-bold">Admin:</span>
                    admin@rapidstrat.test<br>pass: password
                </div>
                <div class="bg-slate-950/60 p-2 rounded border border-slate-800/80">
                    <span class="text-violet-400 block font-sans font-bold">Petugas:</span>
                    petugas@rapidstrat.test<br>pass: password
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
