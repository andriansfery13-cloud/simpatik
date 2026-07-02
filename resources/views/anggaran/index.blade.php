@extends('layouts.app')

@section('title', 'Realisasi Anggaran')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ showImportModal: false }">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Realisasi Anggaran
            </h1>
            <p class="text-sm text-gray-500 mt-1">Monitoring alokasi dan serapan anggaran berdasarkan sumber dana.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button @click="showImportModal = true" class="btn-secondary py-2 px-4 shrink-0 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import
            </button>
            <a href="{{ route('anggaran.export', request()->query()) }}" class="btn-secondary py-2 px-4 shrink-0 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export
            </a>
            <a href="{{ route('anggaran.create') }}" class="btn-primary bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 border-none text-white shrink-0 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Alokasi Anggaran
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    @php
        $serapanPersen = $totalPagu > 0 ? round(($totalRealisasi / $totalPagu) * 100, 1) : 0;
        $sisaAnggaran = max(0, $totalPagu - $totalRealisasi);
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Pagu --}}
        <div class="bg-gradient-to-br from-simpatik-700 to-simpatik-900 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-3 -top-3 opacity-10">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-simpatik-200 text-xs font-medium mb-1 relative z-10">Total Pagu Anggaran</p>
            <h3 class="text-xl font-bold relative z-10 tracking-tight">Rp {{ number_format($totalPagu, 0, ',', '.') }}</h3>
        </div>
        
        {{-- Total Realisasi --}}
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1">Total Realisasi</p>
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Serapan --}}
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1">Persentase Serapan</p>
                    <h3 class="text-xl font-bold {{ $serapanPersen >= 70 ? 'text-simpatik-600' : ($serapanPersen >= 40 ? 'text-yellow-600' : 'text-red-600') }} tracking-tight">{{ $serapanPersen }}%</h3>
                </div>
                <div class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                </div>
            </div>
            <div class="mt-3 w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $serapanPersen >= 70 ? 'bg-simpatik-500' : ($serapanPersen >= 40 ? 'bg-yellow-400' : 'bg-red-500') }}" style="width: {{ min($serapanPersen, 100) }}%"></div>
            </div>
        </div>

        {{-- Sisa Anggaran --}}
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1">Sisa Anggaran</p>
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
        <form action="{{ route('anggaran.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="form-label text-xs">Sumber Dana</label>
                <select name="sumber_dana_id" class="form-select w-full">
                    <option value="">Semua Sumber Dana</option>
                    @foreach($sumberDanas as $sd)
                        <option value="{{ $sd->id }}" {{ request('sumber_dana_id') == $sd->id ? 'selected' : '' }}>{{ $sd->kode }} - {{ $sd->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary py-2 px-6">Filter</button>
            <a href="{{ route('anggaran.index') }}" class="btn-secondary py-2 px-4">Reset</a>
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
                        <th class="min-w-[140px]">Desa</th>
                        <th class="min-w-[130px]">Sumber Dana</th>
                        <th class="w-16 text-center">Tahun</th>
                        <th class="w-28 text-center">Earmark</th>
                        <th class="min-w-[140px] text-right">Pagu</th>
                        <th class="min-w-[140px] text-right">Realisasi</th>
                        <th class="w-20 text-center">Serapan</th>
                        <th class="w-16 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($anggarans as $index => $anggaran)
                        @php
                            $persen = $anggaran->pagu > 0 ? round(($anggaran->realisasi / $anggaran->pagu) * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="text-center text-gray-400 text-xs">{{ $anggarans->firstItem() + $index }}</td>
                            <td>
                                <p class="font-semibold text-gray-800 text-sm">{{ $anggaran->desa->nama }}</p>
                                <p class="text-[10px] text-gray-400">Kec. {{ $anggaran->desa->kecamatan->nama }}</p>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $anggaran->sumberDana->kode }}
                                </span>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $anggaran->sumberDana->nama }}</p>
                            </td>
                            <td class="text-center text-sm text-gray-600">{{ $anggaran->tahun_anggaran }}</td>
                            <td class="text-center">
                                @if($anggaran->status_earmark == 'earmarked')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span> Earmarked
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-50 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Non-Earmarked
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <p class="font-semibold text-gray-800 text-sm tabular-nums">Rp {{ number_format($anggaran->pagu, 0, ',', '.') }}</p>
                            </td>
                            <td class="text-right">
                                <p class="font-semibold text-gray-800 text-sm tabular-nums">Rp {{ number_format($anggaran->realisasi, 0, ',', '.') }}</p>
                            </td>
                            <td class="text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs font-bold {{ $persen >= 70 ? 'text-simpatik-600' : ($persen >= 40 ? 'text-yellow-600' : 'text-red-600') }}">{{ $persen }}%</span>
                                    <div class="w-12 bg-gray-100 h-1 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $persen >= 70 ? 'bg-simpatik-500' : ($persen >= 40 ? 'bg-yellow-400' : 'bg-red-500') }}" style="width: {{ min($persen, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('anggaran.show', $anggaran) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('anggaran.edit', $anggaran) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="font-medium">Belum ada data anggaran.</p>
                                <p class="text-xs mt-1">Klik tombol "Alokasi Anggaran" untuk mulai menambahkan data.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($anggarans as $index => $anggaran)
                @php
                    $persen = $anggaran->pagu > 0 ? round(($anggaran->realisasi / $anggaran->pagu) * 100, 1) : 0;
                @endphp
                <div class="p-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $anggaran->desa->nama }}</p>
                            <p class="text-xs text-gray-400">Kec. {{ $anggaran->desa->kecamatan->nama }} — TA {{ $anggaran->tahun_anggaran }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            @if($anggaran->status_earmark == 'earmarked')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                    Earmarked
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-50 text-gray-600 border border-gray-200">
                                    Non-Earmarked
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $anggaran->sumberDana->kode }}
                        </span>
                        <span class="text-[10px] text-gray-400">{{ $anggaran->sumberDana->nama }}</span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 text-center bg-gray-50 rounded-lg p-3 mb-3">
                        <div>
                            <p class="text-[10px] text-gray-400 mb-0.5">Pagu</p>
                            <p class="text-xs font-bold text-gray-800 tabular-nums">Rp {{ number_format($anggaran->pagu / 1000000, 0, ',', '.') }} Jt</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 mb-0.5">Realisasi</p>
                            <p class="text-xs font-bold text-gray-800 tabular-nums">Rp {{ number_format($anggaran->realisasi / 1000000, 0, ',', '.') }} Jt</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 mb-0.5">Serapan</p>
                            <p class="text-xs font-bold {{ $persen >= 70 ? 'text-simpatik-600' : ($persen >= 40 ? 'text-yellow-600' : 'text-red-600') }}">{{ $persen }}%</p>
                        </div>
                    </div>

                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden mb-3">
                        <div class="h-full rounded-full {{ $persen >= 70 ? 'bg-simpatik-500' : ($persen >= 40 ? 'bg-yellow-400' : 'bg-red-500') }}" style="width: {{ min($persen, 100) }}%"></div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('anggaran.show', $anggaran) }}" class="text-xs text-blue-600 font-medium hover:underline">Detail</a>
                        <a href="{{ route('anggaran.edit', $anggaran) }}" class="text-xs text-yellow-600 font-medium hover:underline">Edit</a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <p class="font-medium">Belum ada data anggaran.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($anggarans->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                {{ $anggarans->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Import Modal --}}
    <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showImportModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showImportModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('anggaran.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Data Anggaran</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-3 rounded-lg text-sm flex justify-between items-center">
                                        <span>Gunakan template standar agar data masuk dengan benar.</span>
                                        <a href="{{ route('anggaran.template') }}" class="font-bold underline hover:text-yellow-900">Download Template</a>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">File Excel (.xlsx, .xls, .csv) <span class="text-red-500">*</span></label>
                                        <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">Import Data</button>
                        <button type="button" @click="showImportModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
