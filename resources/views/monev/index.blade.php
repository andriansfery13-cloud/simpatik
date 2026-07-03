@extends('layouts.app')

@section('title', 'Dashboard Monev Desa')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Dashboard Monev Desa Terintegrasi
            </h1>
            <p class="text-sm text-gray-500 mt-1">Monitoring & Evaluasi Kinerja Administrasi, Keuangan, dan Fisik Pembangunan Desa.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('monev.wizard') }}" class="btn-primary py-2 px-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Mulai Monev Baru
            </a>
        </div>
    </div>

    {{-- AI Ranking Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Desa Terbaik --}}
        <div class="bg-white rounded-xl p-5 shadow-card border border-green-200 relative overflow-hidden text-gray-800">
            <div class="absolute top-0 left-0 w-full h-1 bg-green-500"></div>
            <div class="absolute -right-4 -bottom-4 opacity-5 text-green-600">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-green-700 text-xs font-bold uppercase tracking-wider mb-2">🏆 Desa Terbaik (Rata-rata Tertinggi)</p>
                @if($terbaik)
                    <h3 class="text-2xl font-black text-gray-900">{{ $terbaik->nama }}</h3>
                    <p class="text-gray-500 text-sm mb-4 font-medium">Kec. {{ $terbaik->kecamatan->nama }}</p>
                    <div class="inline-flex items-center px-4 py-1.5 bg-green-50 rounded-lg border border-green-200">
                        <span class="text-2xl font-black text-green-700">{{ number_format($terbaik->rata_rata_skor, 1) }}</span>
                        <span class="text-xs ml-1 font-bold text-green-600">/ 100</span>
                    </div>
                @else
                    <h3 class="text-lg font-medium text-gray-400 italic mt-2">Belum ada data Monev.</h3>
                @endif
            </div>
        </div>

        {{-- Prioritas Pembinaan --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-100 bg-red-50/50 flex items-center justify-between">
                <h3 class="font-bold text-red-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Prioritas Pembinaan (Skor < 75)
                </h3>
            </div>
            <div class="p-0 flex-1 overflow-y-auto max-h-[140px]">
                @php
                    $perluPembinaan = $desas->filter(fn($d) => $d->total_monev > 0 && $d->rata_rata_skor < 75);
                @endphp
                @if($perluPembinaan->count() > 0)
                    <ul class="divide-y divide-gray-100">
                        @foreach($perluPembinaan as $desa)
                        <li class="p-3 hover:bg-gray-50 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $desa->nama }}</p>
                                <p class="text-[10px] text-gray-500">Kec. {{ $desa->kecamatan->nama }}</p>
                            </div>
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">
                                {{ number_format($desa->rata_rata_skor, 1) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="h-full flex items-center justify-center p-6 text-gray-400 text-sm">
                        Tidak ada desa dengan skor di bawah standar.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- History Monev Table --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden mt-6">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Daftar Desa</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-y border-gray-100">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Desa</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kepala Desa</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kegiatan di-Monev</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Rata-rata Skor</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($desas as $desa)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-gray-800">{{ $desa->nama }}</p>
                                <p class="text-xs text-gray-500">Kode: {{ $desa->kode }}</p>
                            </td>
                            <td class="p-4 text-sm text-gray-700">
                                {{ $desa->kepala_desa ?? '-' }}
                            </td>
                            <td class="p-4 text-sm text-gray-700 font-medium">
                                {{ $desa->total_monev }} Kegiatan
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-800">{{ number_format($desa->rata_rata_skor, 1) }}</span> <span class="text-xs text-gray-500">/ 100</span>
                            </td>
                            <td class="p-4 text-center">
                                @php
                                    $score = $desa->rata_rata_skor;
                                    if ($desa->total_monev == 0) {
                                        $cat = 'Belum Ada';
                                        $color = 'gray';
                                    } elseif ($score >= 85) {
                                        $cat = 'Sangat Baik';
                                        $color = 'success';
                                    } elseif ($score >= 70) {
                                        $cat = 'Baik';
                                        $color = 'primary';
                                    } elseif ($score >= 55) {
                                        $cat = 'Cukup';
                                        $color = 'warning';
                                    } else {
                                        $cat = 'Perlu Pembinaan';
                                        $color = 'danger';
                                    }
                                @endphp
                                <span class="badge badge-{{ $color }}">{{ $cat }}</span>
                            </td>
                            <td class="text-center p-4">
                                <a href="{{ route('monev.desa', $desa) }}" class="inline-flex items-center justify-center px-4 py-2 bg-simpatik-50 text-simpatik-700 rounded-lg hover:bg-simpatik-100 font-bold text-sm transition-colors border border-simpatik-200">
                                    Lihat Anggaran &rarr;
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p>Belum ada data desa.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
