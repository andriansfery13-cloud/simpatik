@extends('layouts.app')

@section('title', 'Detail Kegiatan Pembangunan')

@section('content')
<div class="space-y-6 animate-fade-in max-w-6xl mx-auto" x-data="{ showUploadModal: false }">

    {{-- Page Header & Actions --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('kegiatan.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $kegiatan->sumberDana->kode }}
                    </span>
                    <span class="badge badge-{{ $kegiatan->status_color }}">
                        {{ $kegiatan->status_label }}
                    </span>
                </div>
                <h1 class="text-xl font-bold text-gray-800">{{ $kegiatan->nama_kegiatan }}</h1>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    Desa {{ $kegiatan->desa->nama }}, Kec. {{ $kegiatan->desa->kecamatan->nama }}
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="btn-secondary py-2 px-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            <a href="{{ route('kegiatan.edit', $kegiatan) }}#progres_fisik" class="btn-primary py-2 px-4">
                Update Progres
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Progres Card --}}
            <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
                <div class="flex justify-between items-end mb-2">
                    <h3 class="font-bold text-gray-800">Progres Pekerjaan Fisik</h3>
                    <span class="text-2xl font-bold {{ $kegiatan->progres_fisik < 50 ? 'text-yellow-600' : 'text-simpatik-600' }}">{{ $kegiatan->progres_fisik }}%</span>
                </div>
                <div class="progress-bar h-3 mb-4">
                    <div class="progress-bar-fill h-3 {{ $kegiatan->progres_fisik < 50 ? 'bg-yellow-400' : 'bg-simpatik-500' }}" style="width: {{ $kegiatan->progres_fisik }}%"></div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Mulai Pekerjaan</p>
                        <p class="font-medium text-gray-800">{{ $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Target Selesai</p>
                        <p class="font-medium text-gray-800">{{ $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Pagu Anggaran</p>
                        <p class="font-bold text-gray-800">Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Realisasi Keuangan</p>
                        <p class="font-bold text-simpatik-600">Rp {{ number_format($kegiatan->realisasi_anggaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Detail Informasi --}}
            <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Rinci</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Deskripsi Kegiatan</p>
                        <p class="text-sm text-gray-800 leading-relaxed">{{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Pelaksana Pekerjaan</p>
                            <p class="text-sm text-gray-800">{{ $kegiatan->pelaksana ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Penanggung Jawab</p>
                            <p class="text-sm text-gray-800">{{ $kegiatan->penanggung_jawab ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-medium text-gray-500 mb-1">Lokasi Rinci</p>
                            <p class="text-sm text-gray-800">{{ $kegiatan->lokasi ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dokumen & Foto (Placeholder) --}}
            <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                    <h3 class="font-bold text-gray-800">Dokumentasi & Lampiran</h3>
                    <button @click="showUploadModal = true" type="button" class="text-xs text-simpatik-600 font-medium hover:underline">+ Tambah Dokumen</button>
                </div>
                
                @if(isset($kegiatan->dokumens) && count($kegiatan->dokumens) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($kegiatan->dokumens as $dokumen)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden group">
                                <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="block aspect-square overflow-hidden bg-gray-200">
                                    <img src="{{ Storage::url($dokumen->file_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="{{ $dokumen->caption }}">
                                </a>
                                <div class="p-2">
                                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-bold rounded-sm bg-gray-200 text-gray-700 mb-1 uppercase">{{ $dokumen->tipe }}</span>
                                    <p class="text-[10px] text-gray-500 line-clamp-2">{{ $dokumen->caption ?: 'Tidak ada keterangan' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-12 h-12 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm text-gray-400">Belum ada dokumentasi fisik atau dokumen kontrak yang diunggah.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Right Column: Map & Timeline --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Map Location --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm">Titik Koordinat Lokasi</h3>
                </div>
                <div class="h-64 relative bg-gray-100 z-0">
                    @if($kegiatan->latitude && $kegiatan->longitude)
                        <div id="mini-map" class="w-full h-full z-0"></div>
                    @else
                        <div class="flex h-full items-center justify-center flex-col text-gray-400">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-xs font-medium">Koordinat GPS belum diatur</span>
                            <button class="mt-2 text-xs text-simpatik-600 hover:underline">Setel Titik Lokasi</button>
                        </div>
                    @endif
                </div>
                @if($kegiatan->latitude && $kegiatan->longitude)
                    <div class="p-3 bg-gray-50 text-[10px] text-gray-500 flex justify-between">
                        <span>Lat: {{ $kegiatan->latitude }}</span>
                        <span>Lng: {{ $kegiatan->longitude }}</span>
                    </div>
                @endif
            </div>

            {{-- Progres History --}}
            <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2 text-sm">Riwayat Pembaruan</h3>
                
                <div class="relative border-l border-gray-200 ml-3 space-y-6 pb-4">
                    
                    {{-- Default Initial Record --}}
                    <div class="relative">
                        <div class="absolute -left-1.5 mt-1.5 w-3 h-3 rounded-full bg-simpatik-500 border-2 border-white"></div>
                        <div class="pl-5">
                            <p class="text-xs font-bold text-gray-800">Sistem (Otomatis)</p>
                            <p class="text-[10px] text-gray-400 mb-1">{{ $kegiatan->created_at->format('d M Y - H:i') }}</p>
                            <p class="text-xs text-gray-600">Proyek didaftarkan ke dalam sistem. Status: Belum Mulai.</p>
                        </div>
                    </div>
                    
                    {{-- If it has progress > 0 we simulate an update for demo purposes --}}
                    @if($kegiatan->progres_fisik > 0)
                    <div class="relative">
                        <div class="absolute -left-1.5 mt-1.5 w-3 h-3 rounded-full bg-simpatik-500 border-2 border-white"></div>
                        <div class="pl-5">
                            <p class="text-xs font-bold text-gray-800">Pembaruan Progres</p>
                            <p class="text-[10px] text-gray-400 mb-1">{{ $kegiatan->updated_at->format('d M Y - H:i') }}</p>
                            <div class="bg-gray-50 p-2 rounded border border-gray-100 mt-1">
                                <p class="text-xs text-gray-700">Progres fisik diperbarui menjadi <span class="font-bold text-simpatik-600">{{ $kegiatan->progres_fisik }}%</span></p>
                                <p class="text-xs text-gray-700 mt-1">Status: <span class="font-medium capitalize">{{ str_replace('_', ' ', $kegiatan->status) }}</span></p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
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
                    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-simpatik-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Upload Dokumen Laporan</h3>
                                <div class="mt-4 space-y-4">
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
                                        <label class="block text-sm font-medium text-gray-700 mb-1">File Foto / Dokumen</label>
                                        <input type="file" name="file" accept="image/*,.pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-simpatik-50 file:text-simpatik-700 hover:file:bg-simpatik-100" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Singkat</label>
                                        <input type="text" name="caption" class="form-input w-full" placeholder="Cth: Pengecoran hari pertama">
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

@if($kegiatan->latitude && $kegiatan->longitude)
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        initMiniMap();
    });

    // Fallback if Alpine is already initialized
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Alpine) {
            setTimeout(() => {
                initMiniMap();
            }, 100);
        }
    });

    function initMiniMap() {
        const mapElement = document.getElementById('mini-map');
        if (!mapElement || !window.L) return;

        const lat = {{ $kegiatan->latitude }};
        const lng = {{ $kegiatan->longitude }};
        const statusColor = '{{ $kegiatan->status_color === "success" ? "#16a34a" : ($kegiatan->status_color === "warning" ? "#facc15" : ($kegiatan->status_color === "danger" ? "#ef4444" : "#9ca3af")) }}';

        const map = L.map('mini-map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([lat, lng], 15);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        const svgIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: ${statusColor}; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4);"></div>`,
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });

        L.marker([lat, lng], { icon: svgIcon }).addTo(map);
    }
</script>
@endpush
@endif
