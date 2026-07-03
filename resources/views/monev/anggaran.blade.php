@extends('layouts.app')

@section('title', 'Monev - Kegiatan Anggaran')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('monev.index') }}" class="text-sm text-gray-500 hover:text-simpatik-600">Daftar Desa</a>
                <span class="text-gray-400 text-sm">/</span>
                <a href="{{ route('monev.desa', $anggaran->desa_id) }}" class="text-sm text-gray-500 hover:text-simpatik-600">{{ $anggaran->desa->nama }}</a>
                <span class="text-gray-400 text-sm">/</span>
                <span class="text-sm font-bold text-gray-800">{{ $anggaran->sumberDana->kode }} {{ $anggaran->tahun_anggaran }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Monev Kegiatan</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar kegiatan yang telah dievaluasi pada anggaran ini.</p>
        </div>
        <a href="{{ route('monev.desa', $anggaran->desa_id) }}" class="btn-secondary px-4 py-2">&larr; Kembali</a>
    </div>

    {{-- Kegiatan/Monev Table --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-y border-gray-100">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Monev</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kegiatan</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Skor Total</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Kategori</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Penilai</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($monevs as $monev)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4 text-sm text-gray-700">{{ $monev->tanggal_monev->format('d/m/Y') }}</td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-gray-800 line-clamp-2">{{ $monev->kegiatan->nama_kegiatan }}</p>
                                <p class="text-[10px] text-gray-500">Pagu: Rp {{ number_format($monev->kegiatan->pagu_anggaran, 0, ',', '.') }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-bold text-lg {{ $monev->skor_total >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($monev->skor_total, 1) }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold border {{ $monev->kategori_color }}">
                                    {{ $monev->kategori }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-500 text-xs">{{ $monev->user->name }}</td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('monev.show', $monev) }}" class="inline-flex items-center justify-center p-1.5 bg-blue-50 text-blue-600 rounded hover:bg-blue-100" title="Lihat Detail & Insight">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('monev.edit', $monev) }}" class="inline-flex items-center justify-center p-1.5 bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100" title="Edit Hasil Monev">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('monev.destroy', $monev) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data monev ini? Data yang dihapus tidak dapat dikembalikan.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center p-1.5 bg-red-50 text-red-600 rounded hover:bg-red-100" title="Hapus Hasil Monev">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p>Belum ada riwayat monev untuk anggaran ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($monevs->hasPages())
            <div class="mt-4 border-t border-gray-100 pt-4">
                {{ $monevs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
