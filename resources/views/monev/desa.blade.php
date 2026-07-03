@extends('layouts.app')

@section('title', 'Monev - Anggaran Desa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('monev.index') }}" class="text-sm text-gray-500 hover:text-simpatik-600">Daftar Desa</a>
                <span class="text-gray-400 text-sm">/</span>
                <span class="text-sm font-bold text-gray-800">{{ $desa->nama }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Anggaran Desa</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih anggaran untuk melihat rincian riwayat monev kegiatan.</p>
        </div>
        <a href="{{ route('monev.index') }}" class="btn-secondary px-4 py-2">&larr; Kembali</a>
    </div>

    {{-- Anggaran Table --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-y border-gray-100">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Sumber Dana</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pagu Anggaran</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kegiatan di-Monev</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Rata-rata Skor</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($anggarans as $anggaran)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4 font-bold text-gray-800">
                                {{ $anggaran->tahun_anggaran }}
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">{{ $anggaran->sumberDana->kode }}</span>
                                <p class="text-xs text-gray-600 mt-1 font-medium">{{ $anggaran->sumberDana->nama }}</p>
                            </td>
                            <td class="p-4 text-sm text-gray-700 font-medium">
                                Rp {{ number_format($anggaran->pagu, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-sm text-gray-700 font-medium">
                                {{ $anggaran->total_monev }} Kegiatan
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-800">{{ number_format($anggaran->rata_rata_skor, 1) }}</span> <span class="text-xs text-gray-500">/ 100</span>
                            </td>
                            <td class="text-center p-4">
                                <a href="{{ route('monev.anggaran', $anggaran) }}" class="inline-flex items-center justify-center px-4 py-2 bg-simpatik-50 text-simpatik-700 rounded-lg hover:bg-simpatik-100 font-bold text-sm transition-colors border border-simpatik-200">
                                    Lihat Riwayat Kegiatan &rarr;
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p>Belum ada data anggaran untuk desa ini.</p>
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
