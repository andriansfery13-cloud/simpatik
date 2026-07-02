@extends('layouts.app')

@section('title', 'Data Desa')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ showImportModal: false }">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Data Desa
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data administrasi pemerintahan desa.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if(!auth()->user()->isDesa())
            <button @click="showImportModal = true" class="btn-secondary py-2 px-4 shrink-0 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import
            </button>
            @endif
            <a href="{{ route('desa.export', request()->query()) }}" class="btn-secondary py-2 px-4 shrink-0 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export
            </a>
            @if(!auth()->user()->isDesa())
            <a href="{{ route('desa.create') }}" class="btn-primary bg-simpatik-600 hover:bg-simpatik-700 border-none text-white shrink-0 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Desa
            </a>
            @endif
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
        <form action="{{ route('desa.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label text-xs">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input w-full" placeholder="Cari nama desa atau kepala desa...">
            </div>
            @if(auth()->user()->isKabupaten() || auth()->user()->isAdmin())
            <div class="w-full md:w-48">
                <label class="form-label text-xs">Kecamatan</label>
                <select name="kecamatan_id" class="form-select w-full">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>{{ $kecamatan->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <button type="submit" class="btn-primary py-2 px-6">Cari</button>
            <a href="{{ route('desa.index') }}" class="btn-secondary py-2 px-4">Reset</a>
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
                        <th class="min-w-[140px]">Nama Desa</th>
                        <th class="min-w-[120px]">Kecamatan</th>
                        <th class="min-w-[140px]">Kepala Desa</th>
                        <th class="w-24 text-right">Penduduk</th>
                        <th class="w-20 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($desas as $index => $desa)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="text-center text-gray-400 text-xs">{{ $desas->firstItem() + $index }}</td>
                            <td>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 tabular-nums">
                                    {{ $desa->kode }}
                                </span>
                            </td>
                            <td class="font-semibold text-gray-800">{{ $desa->nama }}</td>
                            <td>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-simpatik-50 text-simpatik-700 border border-simpatik-100">
                                    {{ $desa->kecamatan->nama }}
                                </span>
                            </td>
                            <td class="text-gray-700 text-sm">{{ $desa->kepala_desa ?? '-' }}</td>
                            <td class="text-right tabular-nums text-sm text-gray-700">
                                {{ $desa->jumlah_penduduk ? number_format($desa->jumlah_penduduk, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('desa.show', $desa) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('desa.edit', $desa) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <p class="font-medium">Belum ada data desa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($desas as $index => $desa)
                <div class="p-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $desa->nama }}</p>
                            <p class="text-xs text-gray-400">Kode: {{ $desa->kode }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-simpatik-50 text-simpatik-700 border border-simpatik-100">
                            {{ $desa->kecamatan->nama }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                        <div>
                            <p class="text-[10px] text-gray-400">Kepala Desa</p>
                            <p class="text-gray-700 font-medium">{{ $desa->kepala_desa ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400">Penduduk</p>
                            <p class="text-gray-700 font-medium tabular-nums">{{ $desa->jumlah_penduduk ? number_format($desa->jumlah_penduduk, 0, ',', '.') : '-' }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('desa.show', $desa) }}" class="text-xs text-blue-600 font-medium hover:underline">Detail</a>
                        <a href="{{ route('desa.edit', $desa) }}" class="text-xs text-yellow-600 font-medium hover:underline">Edit</a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400"><p class="font-medium">Belum ada data desa.</p></div>
            @endforelse
        </div>

        @if($desas->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">{{ $desas->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- Import Modal --}}
    <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showImportModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showImportModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('desa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-simpatik-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Data Desa</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="bg-blue-50 border border-blue-200 text-blue-700 p-3 rounded-lg text-sm flex justify-between items-center">
                                        <span>Gunakan template standar agar data masuk dengan benar.</span>
                                        <a href="{{ route('desa.template') }}" class="font-bold underline hover:text-blue-900">Download Template</a>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">File Excel (.xlsx, .xls, .csv) <span class="text-red-500">*</span></label>
                                        <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-simpatik-50 file:text-simpatik-700 hover:file:bg-simpatik-100" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-simpatik-600 text-base font-medium text-white hover:bg-simpatik-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-simpatik-500 sm:ml-3 sm:w-auto sm:text-sm">Import Data</button>
                        <button type="button" @click="showImportModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-simpatik-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
