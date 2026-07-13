<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Daftar - SPK Jurusan Kuliah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 flex items-center justify-center p-4 font-inter">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-xl mb-4">
                <svg class="w-9 h-9 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white">SPK Jurusan Kuliah</h1>
            <p class="text-blue-200 text-sm mt-1">Daftar akun baru</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-xl font-black text-gray-900 mb-1">Buat Akun 🎓</h2>
            <p class="text-gray-500 text-sm mb-6">Bergabung dan temukan jurusan terbaikmu</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Nama Anda"
                           class="w-full px-4 py-3 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="nama@email.com"
                           class="w-full px-4 py-3 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- School -->
                <div>
                    <label for="school" class="block text-sm font-semibold text-gray-700 mb-2">Nama Sekolah <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" id="school" name="school" value="{{ old('school') }}"
                           placeholder="SMA/SMK ..."
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"/>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Min. 8 karakter"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"/>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi password"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"/>
                </div>

                <!-- Submit -->
                <button type="submit" id="register-btn"
                        class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700">Masuk</a>
            </p>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-blue-200 text-sm hover:text-white transition-colors">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
