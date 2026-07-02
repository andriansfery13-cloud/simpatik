<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMPATIK') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 flex min-h-screen">

    {{-- Left Side: Branding (Hidden on mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-simpatik-950 via-simpatik-800 to-simpatik-700 text-white p-12 flex-col justify-between relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-simpatik-400/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-2xl">
                    <span class="text-3xl">🌿</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">SIMPATIK</h1>
                    <p class="text-sm text-simpatik-100/80">Sistem Monitoring Pembangunan Terintegrasi Kecamatan</p>
                </div>
            </div>

            <h2 class="text-4xl font-bold leading-tight mb-6">
                Satu Data, Satu Kendali,<br>
                Satu <span class="text-simpatik-300">Platform.</span>
            </h2>
            <p class="text-lg text-simpatik-100 max-w-md">
                Monitoring pembangunan dan pengelolaan anggaran berbasis AI menuju Kabupaten Bandung BEDAS.
            </p>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur border border-white/20">
                    <span class="text-2xl">🏛</span>
                </div>
                <div>
                    <p class="text-sm font-bold">Pemerintah Kabupaten Bandung</p>
                    <p class="text-xs text-simpatik-200">Bangkit, Edukatif, Dinamis, Agamis, dan Sejahtera</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
        <div class="w-full max-w-md">
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 justify-center">
                <div class="w-12 h-12 bg-simpatik-800 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-2xl">🌿</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">SIMPATIK</h1>
                    <p class="text-[10px] text-gray-500 font-medium">Kabupaten Bandung</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
                    <p class="text-sm text-gray-500 mt-1">Silakan masuk menggunakan akun Anda.</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address or Username -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Email / Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input id="login" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-simpatik-500 focus:border-simpatik-500 text-sm" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="Masukkan email atau username" />
                        </div>
                        @error('login')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-simpatik-500 focus:border-simpatik-500 text-sm" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-simpatik-600 shadow-sm focus:ring-simpatik-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-simpatik-600 hover:text-simpatik-800" href="{{ route('password.request') }}">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-simpatik-700 hover:bg-simpatik-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-simpatik-500 transition-colors">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Hanya untuk aparatur pemerintah yang terdaftar.
                    </p>
                </div>
            </div>
            
            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Pemerintah Kabupaten Bandung.<br>
                Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </div>

</body>
</html>
