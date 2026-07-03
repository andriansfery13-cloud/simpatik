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
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-green-100 text-xs font-bold uppercase tracking-wider mb-1">🏆 Desa Terbaik (Rata-rata Tertinggi)</p>
                @if($terbaik)
                    <h3 class="text-2xl font-bold">{{ $terbaik->nama }}</h3>
                    <p class="text-green-100 text-sm mb-3">Kec. {{ $terbaik->kecamatan->nama }}</p>
                    <div class="inline-flex items-center px-3 py-1 bg-white/20 rounded-lg backdrop-blur-sm border border-white/30">
                        <span class="text-xl font-black">{{ number_format($terbaik->rata_rata_skor, 1) }}</span>
                        <span class="text-xs ml-1 font-medium">/ 100</span>
                    </div>
                @else
                    <h3 class="text-lg font-medium text-white/70 italic mt-2">Belum ada data Monev.</h3>
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
            <h3 class="font-bold text-gray-800">Riwayat Monitoring & Evaluasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Desa</th>
                        <th>Kegiatan</th>
                        <th class="text-center">Skor Total</th>
                        <th class="text-center">Kategori</th>
                        <th>Penilai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($monevs as $monev)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="text-gray-600">{{ $monev->tanggal_monev->format('d/m/Y') }}</td>
                        <td>
                            <p class="font-bold text-gray-800">{{ $monev->desa->nama }}</p>
                            <p class="text-[10px] text-gray-500">Kec. {{ $monev->desa->kecamatan->nama }}</p>
                        </td>
                        <td>
                            <p class="font-medium text-gray-700 line-clamp-2 max-w-xs" title="{{ $monev->kegiatan->nama_kegiatan }}">{{ $monev->kegiatan->nama_kegiatan }}</p>
                            <p class="text-[10px] text-gray-500">TA: {{ $monev->kegiatan->tahun_anggaran }}</p>
                        </td>
                        <td class="text-center">
                            <span class="font-bold text-lg {{ $monev->skor_total >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $monev->skor_total }}</span>
                        </td>
                        <td class="text-center">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold border {{ $monev->kategori_color }}">
                                {{ $monev->kategori }}
                            </span>
                        </td>
                        <td class="text-gray-500 text-xs">{{ $monev->user->name }}</td>
                        <td class="text-center">
                            <a href="{{ route('monev.show', $monev) }}" class="inline-flex items-center justify-center p-1.5 bg-blue-50 text-blue-600 rounded hover:bg-blue-100" title="Lihat Detail & Insight">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500 text-sm">Belum ada riwayat Monev.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($monevs->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $monevs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
