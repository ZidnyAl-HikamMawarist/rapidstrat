<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Admin Panel</title>
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
<body class="bg-slate-100 text-slate-800 min-h-screen flex items-center justify-center p-4 selection:bg-indigo-600 selection:text-white relative overflow-hidden">
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 my-8">
        <!-- Logo / Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-600/25 mb-4">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Buat Akun Baru</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Lengkapi data Anda untuk mendaftar akun</p>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-7 shadow-xl shadow-slate-200/50">
            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="John Doe"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('name') border-rose-500 ring-1 ring-rose-500/20 @else border-slate-300 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm transition outline-none font-medium">
                    </div>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1.5 flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('email') border-rose-500 ring-1 ring-rose-500/20 @else border-slate-300 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm transition outline-none font-medium">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1.5 flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="Minimal 6 karakter"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('password') border-rose-500 ring-1 ring-rose-500/20 @else border-slate-300 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm transition outline-none font-medium">
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1.5 flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Ulangi kata sandi"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 rounded-xl text-slate-900 placeholder-slate-400 text-sm transition outline-none font-medium">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full mt-2 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-indigo-600/25 transition transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Daftar Akun</span>
                    <i class="fa-solid fa-user-check text-xs"></i>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 ml-1 transition">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
