@extends('layouts.app')

@section('title', 'Monev - Daftar Desa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('monev.index') }}" class="text-sm text-gray-500 hover:text-simpatik-600">Daftar Kecamatan</a>
                <span class="text-gray-400 text-sm">/</span>
                <span class="text-sm font-bold text-gray-800">{{ $kecamatan->nama }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Desa - Kec. {{ $kecamatan->nama }}</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih desa untuk melihat daftar anggaran dan monev.</p>
        </div>
        <a href="{{ route('monev.index') }}" class="btn-secondary px-4 py-2">&larr; Kembali</a>
    </div>

    {{-- Desa Table --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
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
