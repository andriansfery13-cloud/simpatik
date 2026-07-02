@extends('layouts.app')

@section('title', 'Detail Desa')

@section('content')
<div class="space-y-6" x-data="{ tab: 'profil' }">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 text-simpatik-600 pointer-events-none">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-simpatik-500 to-simpatik-600 rounded-xl flex items-center justify-center text-white shadow-lg text-2xl font-bold">
                {{ substr($desa->nama, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Desa {{ $desa->nama }}</h1>
                <p class="text-sm text-gray-500">Kecamatan {{ $desa->kecamatan->nama }} · Kode: {{ $desa->kode }}</p>
            </div>
        </div>
        <div class="relative z-10 flex gap-2">
            <a href="{{ route('desa.edit', $desa->id) }}" class="btn-secondary py-2 px-4">
                ✏️ Edit Data
            </a>
            <a href="{{ route('desa.index') }}" class="btn-secondary py-2 px-4 text-gray-600">
                Kembali
            </a>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-2 flex overflow-x-auto hide-scrollbar">
        <button @click="tab = 'profil'" :class="{'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'profil', 'text-gray-500 hover:bg-gray-50': tab !== 'profil'}" class="px-6 py-2.5 rounded-lg text-sm whitespace-nowrap transition-colors flex items-center gap-2">
            ℹ️ Profil Dasar
        </button>
        <button @click="tab = 'wilayah'" :class="{'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'wilayah', 'text-gray-500 hover:bg-gray-50': tab !== 'wilayah'}" class="px-6 py-2.5 rounded-lg text-sm whitespace-nowrap transition-colors flex items-center gap-2">
            🗺 Data Wilayah
        </button>
        <button @click="tab = 'aparatur'" :class="{'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'aparatur', 'text-gray-500 hover:bg-gray-50': tab !== 'aparatur'}" class="px-6 py-2.5 rounded-lg text-sm whitespace-nowrap transition-colors flex items-center gap-2">
            👥 Aparatur Desa
        </button>
        <button @click="tab = 'potensi'" :class="{'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'potensi', 'text-gray-500 hover:bg-gray-50': tab !== 'potensi'}" class="px-6 py-2.5 rounded-lg text-sm whitespace-nowrap transition-colors flex items-center gap-2">
            🌾 Potensi & Infrastruktur
        </button>
        <button @click="tab = 'kegiatan'" :class="{'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'kegiatan', 'text-gray-500 hover:bg-gray-50': tab !== 'kegiatan'}" class="px-6 py-2.5 rounded-lg text-sm whitespace-nowrap transition-colors flex items-center gap-2">
            🏗 Kegiatan Pembangunan
        </button>
    </div>

    {{-- Tab Contents --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6 min-h-[400px]">
        
        {{-- TAB 1: Profil Dasar --}}
        <div x-show="tab === 'profil'" class="animate-fade-in">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Umum</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Kepala Desa</p>
                        <p class="text-gray-800 font-medium mt-1">{{ $desa->kepala_desa ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Alamat Kantor</p>
                        <p class="text-gray-800 mt-1">{{ $desa->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">No. Telepon / Kontak</p>
                        <p class="text-gray-800 mt-1">{{ $desa->telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Status Aktif</p>
                        <div class="mt-1">
                            @if($desa->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-error">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3">Statistik Penduduk</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Total Penduduk</p>
                            <p class="text-2xl font-bold text-simpatik-600">{{ number_format($desa->jumlah_penduduk, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Total Laki-laki</p>
                            <p class="text-xl font-bold text-gray-800">{{ number_format($desa->data_penduduk['laki_laki'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Total Perempuan</p>
                            <p class="text-xl font-bold text-gray-800">{{ number_format($desa->data_penduduk['perempuan'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Jumlah KK</p>
                            <p class="text-xl font-bold text-gray-800">{{ number_format($desa->data_penduduk['jumlah_kk'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: Data Wilayah --}}
        <div x-show="tab === 'wilayah'" style="display: none;" class="animate-fade-in">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Data Geografis & Wilayah</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="bg-simpatik-50 p-4 rounded-lg border border-simpatik-100 mb-4">
                        <p class="text-xs text-simpatik-600 font-bold uppercase mb-1">Luas Wilayah Total</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($desa->luas_wilayah, 2, ',', '.') }} <span class="text-base font-normal text-gray-500">Hektar (Ha)</span></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="border border-gray-200 p-3 rounded-lg">
                            <p class="text-[10px] text-gray-500 mb-1">Batas Utara</p>
                            <p class="text-sm font-medium text-gray-800">{{ $desa->data_wilayah['batas_utara'] ?? 'Belum diisi' }}</p>
                        </div>
                        <div class="border border-gray-200 p-3 rounded-lg">
                            <p class="text-[10px] text-gray-500 mb-1">Batas Selatan</p>
                            <p class="text-sm font-medium text-gray-800">{{ $desa->data_wilayah['batas_selatan'] ?? 'Belum diisi' }}</p>
                        </div>
                        <div class="border border-gray-200 p-3 rounded-lg">
                            <p class="text-[10px] text-gray-500 mb-1">Batas Timur</p>
                            <p class="text-sm font-medium text-gray-800">{{ $desa->data_wilayah['batas_timur'] ?? 'Belum diisi' }}</p>
                        </div>
                        <div class="border border-gray-200 p-3 rounded-lg">
                            <p class="text-[10px] text-gray-500 mb-1">Batas Barat</p>
                            <p class="text-sm font-medium text-gray-800">{{ $desa->data_wilayah['batas_barat'] ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 rounded-lg overflow-hidden border border-gray-200 relative h-64 md:h-auto">
                    {{-- Placeholder for Mini Map --}}
                    @if($desa->latitude && $desa->longitude)
                        <div id="desaMap" class="w-full h-full"></div>
                        <div class="absolute bottom-2 left-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold z-[400] shadow">
                            Lat: {{ $desa->latitude }} | Lng: {{ $desa->longitude }}
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="text-sm font-medium">Koordinat belum diatur</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 3: Aparatur Desa --}}
        <div x-show="tab === 'aparatur'" style="display: none;" class="animate-fade-in">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                <h3 class="text-lg font-bold text-gray-800">Struktur Aparatur Pemerintah Desa</h3>
                <button class="text-xs font-bold text-simpatik-600 hover:text-simpatik-700">Update Struktur</button>
            </div>
            
            @if(!empty($desa->data_aparatur) && is_array($desa->data_aparatur))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($desa->data_aparatur as $jabatan => $nama)
                    <div class="bg-white border border-gray-200 p-4 rounded-lg flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-xl text-gray-400">
                            👤
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $jabatan) }}</p>
                            <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $nama }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <div class="text-4xl mb-2">👥</div>
                    <h3 class="text-gray-800 font-bold">Data Aparatur Belum Tersedia</h3>
                    <p class="text-xs text-gray-500 mt-1">Silakan update data aparatur desa melalui menu Edit.</p>
                </div>
            @endif
        </div>

        {{-- TAB 4: Potensi & Infrastruktur --}}
        <div x-show="tab === 'potensi'" style="display: none;" class="animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Potensi --}}
                <div>
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">🌾 Potensi Unggulan</h3>
                        <button class="text-xs font-bold text-simpatik-600 hover:text-simpatik-700">Update</button>
                    </div>
                    @if(!empty($desa->data_potensi) && is_array($desa->data_potensi))
                        <ul class="space-y-3">
                        @foreach($desa->data_potensi as $kategori => $detail)
                            <li class="bg-gray-50 p-3 rounded border border-gray-200">
                                <span class="text-[10px] font-bold uppercase text-gray-500">{{ $kategori }}</span>
                                <p class="text-sm font-medium text-gray-800 mt-1">{{ $detail }}</p>
                            </li>
                        @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic">Belum ada data potensi diinput.</p>
                    @endif
                </div>

                {{-- Infrastruktur --}}
                <div>
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">🏗 Kondisi Infrastruktur</h3>
                        <button class="text-xs font-bold text-simpatik-600 hover:text-simpatik-700">Update</button>
                    </div>
                    @if(!empty($desa->data_infrastruktur) && is_array($desa->data_infrastruktur))
                        <div class="grid grid-cols-2 gap-4">
                        @foreach($desa->data_infrastruktur as $key => $value)
                            <div class="border border-gray-200 p-3 rounded text-center">
                                <p class="text-2xl font-bold text-simpatik-600">{{ $value }}</p>
                                <p class="text-[10px] font-medium text-gray-600 uppercase">{{ str_replace('_', ' ', $key) }}</p>
                            </div>
                        @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Belum ada data infrastruktur diinput.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 5: Kegiatan --}}
        <div x-show="tab === 'kegiatan'" style="display: none;" class="animate-fade-in">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                <h3 class="text-lg font-bold text-gray-800">Daftar Kegiatan Pembangunan</h3>
                <a href="{{ route('kegiatan.create') }}" class="btn-primary py-1.5 px-3 text-xs">➕ Tambah Kegiatan</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="data-table w-full">
                    <thead>
                        <tr>
                            <th>Nama Kegiatan</th>
                            <th>Anggaran</th>
                            <th class="text-center">Progres</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($desa->kegiatans as $kegiatan)
                        <tr>
                            <td class="font-medium text-gray-800 text-sm py-3">{{ $kegiatan->nama_kegiatan }}</td>
                            <td class="text-sm py-3 text-gray-600 font-medium">Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</td>
                            <td class="text-center py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-simpatik-500" style="width: {{ $kegiatan->progres_fisik }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">{{ $kegiatan->progres_fisik }}%</span>
                                </div>
                            </td>
                            <td class="text-center py-3">
                                <span class="badge {{ $kegiatan->status_color }}">{{ $kegiatan->status_label }}</span>
                            </td>
                            <td class="text-center py-3">
                                <a href="{{ route('kegiatan.show', $kegiatan->id) }}" class="text-simpatik-600 hover:text-simpatik-800 bg-simpatik-50 p-1.5 rounded-lg inline-flex" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500 text-sm">Belum ada data kegiatan di desa ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Init map when clicking wilayah tab
        const tabBtns = document.querySelectorAll('[x-show="tab === \'wilayah\'"]');
        let mapInitialized = false;

        document.querySelector('button[@click="tab = \'wilayah\'"]').addEventListener('click', () => {
            if(!mapInitialized && document.getElementById('desaMap')) {
                setTimeout(() => {
                    const lat = {{ $desa->latitude ?? 'null' }};
                    const lng = {{ $desa->longitude ?? 'null' }};
                    
                    if(lat && lng) {
                        const map = L.map('desaMap', { zoomControl: false }).setView([lat, lng], 14);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
                        
                        const icon = L.divIcon({
                            className: 'custom-div-icon',
                            html: '<div style="background-color: #16a34a; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                            iconSize: [14, 14],
                            iconAnchor: [7, 7]
                        });

                        L.marker([lat, lng], {icon: icon})
                         .bindPopup('Kantor Desa {{ $desa->nama }}')
                         .addTo(map);
                    }
                    mapInitialized = true;
                }, 100);
            }
        });
    });
</script>
@endpush
