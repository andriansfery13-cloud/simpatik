@extends('layouts.app')

@section('title', 'Detail Kecamatan ' . $kecamatan->nama)

@section('content')
<div class="space-y-6 animate-fade-in max-w-6xl mx-auto">

    {{-- Page Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('kecamatan.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kecamatan {{ $kecamatan->nama }}</h1>
                <p class="text-sm text-gray-500 mt-1">Kode: {{ $kecamatan->kode }} — Camat: {{ $kecamatan->camat ?? 'Belum diatur' }}</p>
            </div>
        </div>
        <a href="{{ route('kecamatan.edit', $kecamatan) }}" class="btn-secondary py-2 px-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Jumlah Desa</p>
            <p class="text-2xl font-bold text-gray-800">{{ $kecamatan->desas->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Camat</p>
            <p class="text-sm font-bold text-gray-800 truncate">{{ $kecamatan->camat ?? '-' }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Total Penduduk</p>
            <p class="text-xl font-bold text-gray-800">{{ number_format($kecamatan->desas->sum('jumlah_penduduk'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Total Luas</p>
            <p class="text-xl font-bold text-gray-800">{{ round($kecamatan->desas->sum('luas_wilayah'), 2) }} km²</p>
        </div>
    </div>

    {{-- Daftar Desa --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Daftar Desa di Kecamatan {{ $kecamatan->nama }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-12">No</th>
                        <th>Kode</th>
                        <th>Nama Desa</th>
                        <th>Kepala Desa</th>
                        <th class="text-right">Penduduk</th>
                        <th class="text-right">Luas (km²)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kecamatan->desas as $index => $desa)
                        <tr>
                            <td class="text-center text-gray-500">{{ $index + 1 }}</td>
                            <td class="font-medium text-gray-600">{{ $desa->kode }}</td>
                            <td class="font-bold text-gray-800">{{ $desa->nama }}</td>
                            <td class="text-gray-700">{{ $desa->kepala_desa ?? '-' }}</td>
                            <td class="text-right">{{ $desa->jumlah_penduduk ? number_format($desa->jumlah_penduduk, 0, ',', '.') : '-' }}</td>
                            <td class="text-right">{{ $desa->luas_wilayah ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('desa.show', $desa) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded inline-block" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-6 text-gray-400">Belum ada desa terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
