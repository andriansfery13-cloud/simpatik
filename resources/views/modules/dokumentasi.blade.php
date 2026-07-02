@extends('layouts.app')

@section('title', 'Dokumentasi Visual 0-50-100%')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ showUploadModal: false }">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Galeri Bukti Fisik
            </h1>
            <p class="text-sm text-gray-500 mt-1">Verifikasi visual kondisi lapangan sebelum (0%), sedang (50%), dan sesudah (100%) proyek.</p>
        </div>
        <div class="flex gap-2">
            <button @click="showUploadModal = true" class="btn-primary py-2 px-4 shrink-0">Upload Foto Laporan</button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100 flex flex-wrap gap-3">
        <select class="form-select flex-1 min-w-[150px]"><option>Semua Kecamatan</option></select>
        <select class="form-select flex-1 min-w-[150px]"><option>Semua Desa</option></select>
        <select class="form-select flex-1 min-w-[150px]"><option>Proyek Selesai</option></select>
        <button class="btn-primary px-6">Tampilkan</button>
    </div>

    {{-- Gallery Grid (Mockup Data) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Item 1 --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-gray-800">Pembangunan Jembatan Gantung</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Desa Ciwidey · Sumber Dana: DD 2024</p>
                </div>
                <span class="badge badge-success">100% Selesai</span>
            </div>
            <div class="p-4 grid grid-cols-3 gap-2">
                {{-- 0% --}}
                <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1541888086425-d81bb19240f5?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover opacity-80" alt="0%">
                    <div class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[10px] text-center py-1 font-medium">0% (Kondisi Awal)</div>
                </div>
                {{-- 50% --}}
                <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover opacity-90" alt="50%">
                    <div class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[10px] text-center py-1 font-medium">50% (Pengerjaan)</div>
                </div>
                {{-- 100% --}}
                <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border border-simpatik-500 border-2 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1513828742140-ccaa15f4030f?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover" alt="100%">
                    <div class="absolute bottom-0 inset-x-0 bg-simpatik-600 text-white text-[10px] text-center py-1 font-medium">100% (Selesai)</div>
                </div>
            </div>
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-500 flex justify-between">
                <span>Diperbarui: 12 Ags 2024</span>
                <a href="#" class="text-blue-600 font-medium hover:underline">Lihat Detail Verifikasi</a>
            </div>
        </div>

        {{-- Item 2 --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-gray-800">Pengecoran Jalan Usaha Tani</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Desa Soreang · Sumber Dana: Banprov 2024</p>
                </div>
                <span class="badge badge-info">50% Berjalan</span>
            </div>
            <div class="p-4 grid grid-cols-3 gap-2">
                {{-- 0% --}}
                <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover opacity-80" alt="0%">
                    <div class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[10px] text-center py-1 font-medium">0% (Kondisi Awal)</div>
                </div>
                {{-- 50% --}}
                <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border border-simpatik-500 border-2 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1590495914104-e5e7bc08cb6d?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover opacity-90" alt="50%">
                    <div class="absolute bottom-0 inset-x-0 bg-simpatik-600 text-white text-[10px] text-center py-1 font-medium">50% (Pengerjaan)</div>
                </div>
                {{-- 100% --}}
                <div class="relative group aspect-square bg-gray-50 rounded-lg overflow-hidden border border-gray-200 border-dashed flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[10px] font-medium">Menunggu</span>
                </div>
            </div>
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-500 flex justify-between">
                <span>Diperbarui: 02 Sep 2024</span>
                <a href="#" class="text-blue-600 font-medium hover:underline">Lihat Detail Verifikasi</a>
            </div>
        </div>

    </div>

    {{-- Upload Modal --}}
    <div x-show="showUploadModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showUploadModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showUploadModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showUploadModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-simpatik-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Upload Foto/Dokumen Laporan</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kegiatan <span class="text-red-500">*</span></label>
                                        <select name="kegiatan_id" class="form-select w-full" required>
                                            <option value="">-- Pilih Kegiatan Pembangunan --</option>
                                            @foreach($kegiatans as $keg)
                                                <option value="{{ $keg->id }}">{{ $keg->nama_kegiatan }} (Desa {{ $keg->desa->nama }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Dokumen</label>
                                        <select name="tipe" class="form-select w-full" required>
                                            <option value="sebelum">Kondisi Awal (0%)</option>
                                            <option value="proses">Dalam Pengerjaan (50%)</option>
                                            <option value="sesudah">Kondisi Selesai (100%)</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">File Foto / Dokumen <span class="text-red-500">*</span></label>
                                        <input type="file" name="file" accept="image/*,.pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-simpatik-50 file:text-simpatik-700 hover:file:bg-simpatik-100" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Singkat</label>
                                        <input type="text" name="caption" class="form-input w-full" placeholder="Cth: Dokumentasi dropping material besi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-simpatik-600 text-base font-medium text-white hover:bg-simpatik-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-simpatik-500 sm:ml-3 sm:w-auto sm:text-sm">Upload</button>
                        <button type="button" @click="showUploadModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-simpatik-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
