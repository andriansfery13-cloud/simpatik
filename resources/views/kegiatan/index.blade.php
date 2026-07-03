@extends('layouts.app')

@section('title', 'Data Kegiatan Pembangunan')

@section('content')
    <div class="space-y-6 animate-fade-in" x-data="{ showImportModal: false, showAiImportModal: false, showFilter: {{ count(request()->except('page')) > 0 ? 'true' : 'false' }} }">

        {{-- Page Header --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    Data Kegiatan Pembangunan
                </h1>
                <p class="text-sm text-gray-500 mt-1">Monitoring seluruh kegiatan pembangunan fisik di wilayah.</p>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
                <button @click="showFilter = !showFilter" class="btn-secondary py-1.5 px-3 text-xs shrink-0 flex items-center gap-1.5" :class="{'bg-gray-100': showFilter}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Pencarian
                </button>
                <button @click="showImportModal = true" class="btn-secondary py-1.5 px-3 text-xs shrink-0 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import Excel
                </button>
                <button @click="showAiImportModal = true"
                    class="btn-secondary bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100 py-1.5 px-3 text-xs shrink-0 flex items-center gap-1.5"
                    title="Import otomatis dengan membaca file PDF">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Import PDF (AI)
                </button>
                <a href="{{ route('kegiatan.export', request()->query()) }}"
                    class="btn-secondary py-1.5 px-3 text-xs shrink-0 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </a>
                <a href="{{ route('kegiatan.create') }}"
                    class="btn-primary bg-blue-600 hover:bg-blue-700 border-none text-white py-1.5 px-3 text-xs shrink-0 flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kegiatan
                </a>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div x-show="showFilter" x-transition.opacity.duration.300ms style="display: none;" class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <form action="{{ route('kegiatan.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-center gap-2 w-full">
                <div class="flex-1 min-w-[150px]">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input w-full text-sm py-2" placeholder="Cari nama kegiatan...">
                </div>
                
                @if(auth()->user()->isKecamatan() || auth()->user()->isKabupaten())
                <div class="w-full md:w-36 shrink-0">
                    <select name="desa_id" class="form-select w-full text-sm py-2">
                        <option value="">Semua Desa</option>
                        @foreach($desas as $desa)
                        <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>{{ $desa->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="w-full md:w-32 shrink-0">
                    <select name="sumber_dana_id" class="form-select w-full text-sm py-2">
                        <option value="">Semua Dana</option>
                        @foreach($sumberDanas as $sd)
                        <option value="{{ $sd->id }}" {{ request('sumber_dana_id') == $sd->id ? 'selected' : '' }}>{{ $sd->kode }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-32 shrink-0">
                    <select name="periode_anggaran" class="form-select w-full text-sm py-2">
                        <option value="">Semua Tahap</option>
                        <option value="Tahap 1" {{ request('periode_anggaran') == 'Tahap 1' ? 'selected' : '' }}>Tahap 1</option>
                        <option value="Tahap 2" {{ request('periode_anggaran') == 'Tahap 2' ? 'selected' : '' }}>Tahap 2</option>
                    </select>
                </div>

                <div class="w-full md:w-32 shrink-0">
                    <select name="status" class="form-select w-full text-sm py-2">
                        <option value="">Semua Status</option>
                        <option value="belum_mulai" {{ request('status') == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                
                <div class="flex gap-2 shrink-0">
                    <button type="submit" class="btn-primary py-2 px-4 text-sm bg-green-700 hover:bg-green-800 border-none">Cari</button>
                    <a href="{{ route('kegiatan.index') }}" class="btn-secondary py-2 px-4 text-sm text-green-700 border-green-700 hover:bg-green-50">Reset</a>
                </div>
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
                            <th class="min-w-[200px]">Nama Kegiatan</th>
                            <th class="min-w-[100px]">Desa</th>
                            <th class="w-24 text-center">Sumber</th>
                            <th class="min-w-[130px] text-right">Pagu</th>
                            <th class="w-32 text-center">Progres</th>
                            <th class="w-24 text-center">Status</th>
                            <th class="w-20 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($kegiatans as $index => $kegiatan)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="text-center text-gray-400 text-xs">{{ $kegiatans->firstItem() + $index }}</td>
                                <td>
                                    <a href="{{ route('kegiatan.show', $kegiatan) }}"
                                        class="font-semibold text-gray-800 hover:text-simpatik-600 transition-colors text-sm">
                                        {{ $kegiatan->nama_kegiatan }}
                                    </a>
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        {{ $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->format('d M') : '?' }} —
                                        {{ $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->format('d M Y') : '?' }}</p>
                                </td>
                                <td>
                                    <p class="text-sm text-gray-700">{{ $kegiatan->desa->nama }}</p>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $kegiatan->sumberDana->kode }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <p class="font-semibold text-gray-800 text-sm tabular-nums">Rp
                                        {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center gap-2 justify-center">
                                        <div class="w-20 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-300 {{ $kegiatan->progres_fisik >= 80 ? 'bg-simpatik-500' : ($kegiatan->progres_fisik >= 40 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                                style="width: {{ $kegiatan->progres_fisik }}%"></div>
                                        </div>
                                        <span
                                            class="text-xs font-bold text-gray-600 tabular-nums w-10 text-right">{{ $kegiatan->progres_fisik }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $kegiatan->status_color }}">{{ $kegiatan->status_label }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('kegiatan.show', $kegiatan) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                            title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('kegiatan.edit', $kegiatan) }}"
                                            class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('kegiatan.destroy', $kegiatan) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <p class="font-medium">Belum ada data kegiatan.</p>
                                    <p class="text-xs mt-1">Klik tombol "Tambah Kegiatan" untuk mulai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($kegiatans as $index => $kegiatan)
                    <a href="{{ route('kegiatan.show', $kegiatan) }}" class="block p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <p class="font-semibold text-gray-800 text-sm flex-1 mr-2">{{ $kegiatan->nama_kegiatan }}</p>
                            <span
                                class="badge badge-{{ $kegiatan->status_color }} shrink-0">{{ $kegiatan->status_label }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-3">{{ $kegiatan->desa->nama }} · {{ $kegiatan->sumberDana->kode }}
                        </p>
                        <div class="grid grid-cols-2 gap-3 bg-gray-50 rounded-lg p-3 mb-3">
                            <div>
                                <p class="text-[10px] text-gray-400 mb-0.5">Pagu</p>
                                <p class="text-xs font-bold text-gray-800 tabular-nums">Rp
                                    {{ number_format($kegiatan->pagu_anggaran / 1000000, 0, ',', '.') }} Jt</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 mb-0.5">Progres Fisik</p>
                                <p
                                    class="text-xs font-bold {{ $kegiatan->progres_fisik >= 80 ? 'text-simpatik-600' : ($kegiatan->progres_fisik >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $kegiatan->progres_fisik }}%</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $kegiatan->progres_fisik >= 80 ? 'bg-simpatik-500' : ($kegiatan->progres_fisik >= 40 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                style="width: {{ $kegiatan->progres_fisik }}%"></div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <p class="font-medium">Belum ada data kegiatan.</p>
                    </div>
                @endforelse
            </div>

            @if($kegiatans->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">{{ $kegiatans->withQueryString()->links() }}</div>
            @endif
        </div>

        {{-- Import Modal --}}
        <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showImportModal" x-transition.opacity
                    class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showImportModal = false"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showImportModal" x-transition.scale.origin.bottom
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('kegiatan.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Data
                                        Kegiatan</h3>
                                    <div class="mt-4 space-y-4">
                                        <div
                                            class="bg-blue-50 border border-blue-200 text-blue-700 p-3 rounded-lg text-sm flex justify-between items-center">
                                            <span>Gunakan template standar agar data masuk dengan benar.</span>
                                            <a href="{{ route('kegiatan.template') }}"
                                                class="font-bold underline hover:text-blue-900">Download Template</a>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">File Excel (.xlsx,
                                                .xls, .csv) <span class="text-red-500">*</span></label>
                                            <input type="file" name="file_excel" accept=".xlsx,.xls,.csv"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Import
                                Data</button>
                            <button type="button" @click="showImportModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- AI PDF Import Modal --}}
        <div x-show="showAiImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data="{ isUploading: false }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAiImportModal" x-transition.opacity
                    class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                    @click="if(!isUploading) showAiImportModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showAiImportModal" x-transition.scale.origin.bottom
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('kegiatan.import.ai') }}" method="POST" enctype="multipart/form-data"
                        @submit="isUploading = true">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                            <!-- Loading Overlay -->
                            <div x-show="isUploading"
                                class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex flex-col items-center justify-center">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-purple-600 mb-3"></div>
                                <p class="text-sm font-semibold text-purple-700 animate-pulse">AI sedang membaca dokumen
                                    Anda...</p>
                                <p class="text-xs text-gray-500 mt-1">Ini mungkin memakan waktu hingga 30 detik.</p>
                            </div>

                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Data
                                        dengan AI</h3>
                                    <div class="mt-4 space-y-4">
                                        <div
                                            class="bg-purple-50 border border-purple-200 text-purple-700 p-3 rounded-lg text-sm">
                                            Upload dokumen <strong>Laporan / DPA berbentuk PDF digital</strong>. Sistem AI
                                            akan membaca teks secara otomatis dan memasukkannya ke database kegiatan.
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">File Dokumen (.pdf)
                                                <span class="text-red-500">*</span></label>
                                            <input type="file" name="file_pdf" accept=".pdf"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="isUploading"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">Ekstrak
                                & Simpan</button>
                            <button type="button" @click="if(!isUploading) showAiImportModal = false"
                                :disabled="isUploading"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection