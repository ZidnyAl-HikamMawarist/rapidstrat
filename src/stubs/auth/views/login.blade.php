<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Admin Panel</title>
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
<body class="bg-slate-100 text-slate-800 min-h-screen flex items-center justify-center p-4 selection:bg-indigo-600 selection:text-white relative overflow-hidden">
    <!-- Ambient Background Accents -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-600/25 mb-4">
                <i class="fa-solid fa-layer-group text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Admin Panel</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Silakan masuk dengan akun terdaftar Anda</p>
        </div>

        <!-- Card Container (Light Mode) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-7 shadow-xl shadow-slate-200/50">
            <!-- Alert Session / Error -->
            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-3 font-medium">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-3 font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('email') border-rose-500 ring-1 ring-rose-500/20 @else border-slate-300 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm transition outline-none font-medium">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1.5 flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border @error('password') border-rose-500 ring-1 ring-rose-500/20 @else border-slate-300 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm transition outline-none font-medium">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <i id="eye-icon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1.5 flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600 hover:text-slate-900 font-medium">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-indigo-600/25 transition transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Masuk ke Akun</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            @if(Route::has('register'))
                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 ml-1 transition">Daftar sekarang</a>
                </div>
            @endif
        </div>

        <!-- Demo Accounts Box (Light Mode) -->
        <div class="mt-6 p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm text-xs text-slate-500">
            <div class="flex items-center gap-2 font-bold text-slate-700 mb-2">
                <i class="fa-solid fa-key text-indigo-600"></i> Akun Pengujian (Demo):
            </div>
            <div class="grid grid-cols-2 gap-2 text-[11px] font-mono">
                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                    <span class="text-indigo-600 block font-sans font-bold">Admin:</span>
                    admin@rapidstrat.test<br>pass: password
                </div>
                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                    <span class="text-violet-600 block font-sans font-bold">Petugas:</span>
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
