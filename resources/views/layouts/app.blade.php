<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIMPATIK - Sistem Monitoring Pembangunan Terintegrasi Kecamatan Kabupaten Bandung BEDAS">

    <title>{{ config('app.name', 'SIMPATIK') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: true, sidebarMobileOpen: false }">
    <div class="flex min-h-screen bg-gray-100">

        {{-- Sidebar --}}
        <aside class="sidebar"
               :class="{ '-translate-x-full': !sidebarOpen && window.innerWidth >= 1024, '-translate-x-full lg:translate-x-0': !sidebarMobileOpen && window.innerWidth < 1024 }"
               x-show="sidebarOpen || sidebarMobileOpen"
               x-transition>

            {{-- Logo Section --}}
            <div class="p-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-2xl">🌿</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight">SIMPATIK</h1>
                        <p class="text-[10px] text-white/60 leading-tight">Sistem Monitoring Pembangunan<br>Terintegrasi Kecamatan</p>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-simpatik-600/50 text-[10px] font-medium text-white/90">
                        🏛 Kabupaten Bandung BEDAS
                    </span>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="py-3" x-data="{ openMenu: '{{ request()->routeIs('dashboard') ? 'dashboard' : '' }}' }">
                <p class="px-4 mb-2 text-[10px] font-semibold text-white/40 uppercase tracking-wider">Menu Utama</p>

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="sidebar-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>

                {{-- Data Kecamatan (Super Admin Only) --}}
                @if(auth()->user()->isAdmin())
                <div x-data="{ open: {{ request()->routeIs('kecamatan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="sidebar-menu-item w-full {{ request()->routeIs('kecamatan.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="flex-1 text-left">Data Kecamatan</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1">
                        <a href="{{ route('kecamatan.index') }}" class="sidebar-submenu-item">📋 Daftar Kecamatan</a>
                        <a href="{{ route('kecamatan.create') }}" class="sidebar-submenu-item">➕ Tambah Kecamatan</a>
                    </div>
                </div>
                @endif

                {{-- Data Desa --}}
                <div x-data="{ open: {{ request()->routeIs('desa.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="sidebar-menu-item w-full {{ request()->routeIs('desa.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="flex-1 text-left">Data Desa</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1">
                        <a href="{{ route('desa.index') }}" class="sidebar-submenu-item">📋 Daftar Desa</a>
                        <a href="{{ route('desa.create') }}" class="sidebar-submenu-item">➕ Tambah Desa</a>
                    </div>
                </div>

                {{-- Data Kegiatan --}}
                <div x-data="{ open: {{ request()->routeIs('kegiatan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="sidebar-menu-item w-full {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="flex-1 text-left">Data Kegiatan</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1">
                        <a href="{{ route('kegiatan.index') }}" class="sidebar-submenu-item">📋 Daftar Kegiatan</a>
                        <a href="{{ route('kegiatan.create') }}" class="sidebar-submenu-item">➕ Tambah Kegiatan</a>
                    </div>
                </div>

                {{-- Monitoring Progres --}}
                <a href="{{ route('kegiatan.index', ['status' => 'berjalan']) }}" class="sidebar-menu-item">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Monitoring Progres</span>
                </a>

                {{-- Realisasi Anggaran --}}
                <div x-data="{ open: {{ request()->routeIs('anggaran.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="sidebar-menu-item w-full {{ request()->routeIs('anggaran.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="flex-1 text-left">Realisasi Anggaran</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1">
                        <a href="{{ route('anggaran.index') }}" class="sidebar-submenu-item">📋 Daftar Anggaran</a>
                        <a href="{{ route('anggaran.create') }}" class="sidebar-submenu-item">➕ Tambah Anggaran</a>
                    </div>
                </div>

                {{-- Dokumentasi --}}
                <a href="{{ route('dokumentasi.index') }}" class="sidebar-menu-item {{ request()->routeIs('dokumentasi.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Dokumentasi</span>
                </a>

                {{-- Peta Digital GIS --}}
                <a href="{{ route('gis.index') }}" class="sidebar-menu-item {{ request()->routeIs('gis.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Peta Digital GIS</span>
                </a>

                {{-- Monitoring & Evaluasi --}}
                @if(auth()->user()->isKabupaten() || auth()->user()->isKecamatan())
                <a href="{{ route('monev.index') }}" class="sidebar-menu-item {{ request()->routeIs('monev.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Monev Desa</span>
                </a>
                @endif

                {{-- Early Warning System --}}
                <a href="{{ route('early.warning') }}" class="sidebar-menu-item {{ request()->routeIs('early.warning') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Early Warning System</span>
                </a>

                {{-- AI Analytics --}}
                <a href="{{ route('ai.analytics') }}" class="sidebar-menu-item {{ request()->routeIs('ai.analytics') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    <span>AI Analytics</span>
                    <span class="ml-auto px-2 py-0.5 bg-yellow-500 text-[9px] font-bold rounded-full text-white">NEW</span>
                </a>

                {{-- Laporan & Statistik --}}
                <a href="{{ route('laporan.index') }}" class="sidebar-menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Laporan & Statistik</span>
                </a>

                {{-- Transparansi Publik --}}
                <a href="{{ route('transparansi.index') }}" target="_blank" class="sidebar-menu-item">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Transparansi Publik</span>
                </a>

                {{-- Manajemen Pengguna --}}
                @if(auth()->user()->isKabupaten() || auth()->user()->isKecamatan())
                <a href="{{ route('users.index') }}" class="sidebar-menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Manajemen Pengguna</span>
                </a>
                @endif

                {{-- Pengaturan Sistem (Super Admin) --}}
                @if(auth()->user()->isKabupaten())
                <a href="{{ route('settings.integrations') }}" class="sidebar-menu-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Pengaturan Sistem</span>
                </a>
                @endif

                {{-- Pengaturan Profil --}}
                <a href="{{ route('profile.edit') }}" class="sidebar-menu-item">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Profil Saya</span>
                </a>

                {{-- Tenant Indicator --}}
                <div class="mt-4 mx-4 p-3 bg-white/5 rounded-lg border border-white/10">
                    <p class="text-[10px] text-white/40 uppercase font-semibold mb-2">TENANT</p>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                        <span class="text-xs text-white/80">
                            @if(auth()->user()->isDesa() && auth()->user()->desa)
                                Desa {{ auth()->user()->desa->nama }}
                            @elseif(auth()->user()->kecamatan)
                                Kecamatan {{ auth()->user()->kecamatan->nama }}
                            @else
                                Kabupaten Bandung
                            @endif
                        </span>
                    </div>
                </div>
            </nav>
        </aside>

        {{-- Mobile sidebar overlay --}}
        <div x-show="sidebarMobileOpen" @click="sidebarMobileOpen = false"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition.opacity></div>

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

            {{-- Top Header Bar --}}
            <header class="bg-gradient-to-r from-simpatik-950 via-simpatik-900 to-simpatik-800 text-white shadow-lg">
                <div class="flex items-center justify-between px-4 py-2.5">
                    {{-- Mobile menu button --}}
                    <button @click="sidebarMobileOpen = !sidebarMobileOpen" class="lg:hidden p-2 rounded-lg hover:bg-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    {{-- Kabupaten Branding --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center border border-white/20">
                            <span class="text-xl">🏛</span>
                        </div>
                        <div class="hidden sm:block">
                            <h2 class="text-sm font-bold tracking-wide">KABUPATEN BANDUNG BEDAS</h2>
                            <p class="text-[10px] text-white/60">Bangkit, Edukatif, Dinamis, Agamis, dan Sejahtera</p>
                        </div>
                    </div>

                    {{-- Center: Date --}}
                    <div class="hidden md:flex items-center gap-2 px-4 py-1.5 bg-white/10 rounded-lg border border-white/10">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-xs">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                        <span class="text-xs text-white/50">{{ now()->format('H:i') }} WIB</span>
                    </div>

                    {{-- Right: User Info --}}
                    <div class="flex items-center gap-3">
                        {{-- Notifications --}}
                        <button class="relative p-2 rounded-lg hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="notification-badge">4</span>
                        </button>

                        {{-- User --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white/10 transition">
                                <div class="w-8 h-8 bg-simpatik-500 rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-xs font-medium">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-white/60">{{ auth()->user()->role_label }}</p>
                                </div>
                                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    ⚙️ Pengaturan Profil
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 md:p-6 content-area">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex items-center gap-2 animate-slide-up">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg flex items-center gap-2 animate-slide-up">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="bg-gradient-to-r from-simpatik-950 to-simpatik-900 text-white py-4 px-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">🌿</span>
                        <div>
                            <p class="text-xs font-medium">Bersama SIMPATIK, kita wujudkan pembangunan desa</p>
                            <p class="text-[10px] text-white/50">yang transparan, akuntabel, dan berdampak nyata.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6 text-[10px] text-white/40">
                        <span class="flex items-center gap-1">🔓 Transparan</span>
                        <span class="flex items-center gap-1">✅ Akuntabel</span>
                        <span class="flex items-center gap-1">⚡ Efektif</span>
                        <span class="flex items-center gap-1">🤖 Inovatif</span>
                        <span class="flex items-center gap-1">🤝 Kolaboratif</span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-simpatik-400">Bedas!</p>
                        <p class="text-[10px] text-white/40">KABUPATEN BANDUNG</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Esri Leaflet for ArcGIS Integration -->
    <script src="https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js"></script>

    @livewireScripts
    @stack('scripts')
</body>
</html>
