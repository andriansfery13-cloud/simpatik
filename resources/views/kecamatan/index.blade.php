@extends('layouts.app')

@section('title', 'Data Kecamatan')

@section('content')
<div class="space-y-6 animate-fade-in">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Data Kecamatan
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data wilayah kecamatan di Kabupaten Bandung.</p>
        </div>
        <a href="{{ route('kecamatan.create') }}" class="btn-primary bg-simpatik-600 hover:bg-simpatik-700 border-none text-white shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Kecamatan
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
        <form action="{{ route('kecamatan.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label text-xs">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input w-full" placeholder="Cari nama kecamatan atau camat...">
            </div>
            <button type="submit" class="btn-primary py-2 px-6">Cari</button>
            <a href="{{ route('kecamatan.index') }}" class="btn-secondary py-2 px-4">Reset</a>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th class="min-w-[80px]">Kode</th>
                        <th class="min-w-[160px]">Nama Kecamatan</th>
                        <th class="min-w-[180px]">Nama Camat</th>
                        <th class="w-28 text-center">Jumlah Desa</th>
                        <th class="w-20 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kecamatans as $index => $kecamatan)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="text-center text-gray-400 text-xs">{{ $kecamatans->firstItem() + $index }}</td>
                            <td>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 tabular-nums">
                                    {{ $kecamatan->kode }}
                                </span>
                            </td>
                            <td class="font-semibold text-gray-800">{{ $kecamatan->nama }}</td>
                            <td class="text-gray-700 text-sm">{{ $kecamatan->camat ?? '-' }}</td>
                            <td class="text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $kecamatan->desas_count }} Desa
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('kecamatan.show', $kecamatan) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('kecamatan.edit', $kecamatan) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path></svg>
                                <p class="font-medium">Belum ada data kecamatan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($kecamatans as $index => $kecamatan)
                <div class="p-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $kecamatan->nama }}</p>
                            <p class="text-xs text-gray-400">Kode: {{ $kecamatan->kode }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $kecamatan->desas_count }} Desa
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Camat: {{ $kecamatan->camat ?? '-' }}</p>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('kecamatan.show', $kecamatan) }}" class="text-xs text-blue-600 font-medium hover:underline">Detail</a>
                        <a href="{{ route('kecamatan.edit', $kecamatan) }}" class="text-xs text-yellow-600 font-medium hover:underline">Edit</a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400"><p class="font-medium">Belum ada data kecamatan.</p></div>
            @endforelse
        </div>

        @if($kecamatans->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">{{ $kecamatans->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
