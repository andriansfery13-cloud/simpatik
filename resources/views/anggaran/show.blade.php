@extends('layouts.app')

@section('title', 'Detail Alokasi Anggaran')

@section('content')
<div class="space-y-6 animate-fade-in max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('anggaran.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $anggaran->sumberDana->kode }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $anggaran->status_earmark == 'earmarked' ? 'bg-orange-50 text-orange-700 border border-orange-200' : 'bg-gray-50 text-gray-700 border border-gray-200' }}">
                        {{ ucfirst($anggaran->status_earmark) }}
                    </span>
                </div>
                <h1 class="text-xl font-bold text-gray-800">{{ $anggaran->sumberDana->nama }}</h1>
                <p class="text-sm text-gray-500 mt-1">Desa {{ $anggaran->desa->nama }}, Kec. {{ $anggaran->desa->kecamatan->nama }} — TA {{ $anggaran->tahun_anggaran }}</p>
            </div>
        </div>
        <a href="{{ route('anggaran.edit', $anggaran) }}" class="btn-secondary py-2 px-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Pagu --}}
        <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Total Pagu</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($anggaran->pagu, 0, ',', '.') }}</p>
        </div>

        {{-- Realisasi --}}
        <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Total Realisasi</p>
            <p class="text-2xl font-bold text-simpatik-600">Rp {{ number_format($anggaran->realisasi, 0, ',', '.') }}</p>
        </div>

        {{-- Serapan --}}
        @php $serapan = $anggaran->pagu > 0 ? round(($anggaran->realisasi / $anggaran->pagu) * 100, 1) : 0; @endphp
        <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Persentase Serapan</p>
            <p class="text-2xl font-bold {{ $serapan >= 80 ? 'text-simpatik-600' : ($serapan >= 50 ? 'text-yellow-600' : 'text-red-600') }}">{{ $serapan }}%</p>
            <div class="progress-bar mt-2 h-2">
                <div class="progress-bar-fill h-2 {{ $serapan >= 80 ? 'bg-simpatik-500' : ($serapan >= 50 ? 'bg-yellow-400' : 'bg-red-500') }}" style="width: {{ min($serapan, 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Rinci</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Sumber Dana</p>
                <p class="text-gray-800">{{ $anggaran->sumberDana->kode }} — {{ $anggaran->sumberDana->nama }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Desa</p>
                <p class="text-gray-800">{{ $anggaran->desa->nama }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Kecamatan</p>
                <p class="text-gray-800">{{ $anggaran->desa->kecamatan->nama }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Tahun Anggaran</p>
                <p class="text-gray-800">{{ $anggaran->tahun_anggaran }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Status Earmark</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $anggaran->status_earmark == 'earmarked' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst(str_replace('-', ' ', $anggaran->status_earmark)) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Selisih (Sisa Pagu)</p>
                <p class="font-bold text-gray-800">Rp {{ number_format(max(0, $anggaran->pagu - $anggaran->realisasi), 0, ',', '.') }}</p>
            </div>
            @if($anggaran->keterangan)
            <div class="md:col-span-2">
                <p class="text-xs font-medium text-gray-500 mb-1">Keterangan</p>
                <p class="text-gray-800">{{ $anggaran->keterangan }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
