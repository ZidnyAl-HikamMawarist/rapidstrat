<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - {{ config('app.name', 'RapidStrat') }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white relative overflow-hidden">
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 my-8">
        <!-- Logo / Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-lg shadow-indigo-500/30 mb-4 ring-1 ring-white/20">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Buat Akun Baru</h1>
            <p class="text-sm text-slate-400 mt-1">Lengkapi data Anda untuk mendaftar akun</p>
        </div>

        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-7 shadow-2xl shadow-black/50">
            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="John Doe"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border @error('name') border-rose-500/60 ring-1 ring-rose-500/40 @else border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror rounded-xl text-slate-100 placeholder-slate-500 text-sm transition outline-none">
                    </div>
                    @error('name')
                        <p class="text-xs text-rose-400 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border @error('email') border-rose-500/60 ring-1 ring-rose-500/40 @else border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror rounded-xl text-slate-100 placeholder-slate-500 text-sm transition outline-none">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-400 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="Minimal 6 karakter"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border @error('password') border-rose-500/60 ring-1 ring-rose-500/40 @else border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror rounded-xl text-slate-100 placeholder-slate-500 text-sm transition outline-none">
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-400 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Ulangi kata sandi"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl text-slate-100 placeholder-slate-500 text-sm transition outline-none">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full mt-2 py-2.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Daftar Akun</span>
                    <i class="fa-solid fa-user-check text-xs"></i>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-indigo-400 hover:text-indigo-300 ml-1 transition">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
