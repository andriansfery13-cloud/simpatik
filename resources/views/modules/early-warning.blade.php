@extends('layouts.app')

@section('title', 'Early Warning System (EWS)')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl border-l-4 border-red-500 shadow-card">
        <div>
            <h1 class="text-2xl font-bold text-red-600 flex items-center gap-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Early Warning System
            </h1>
            <p class="text-sm text-gray-600 mt-1">Sistem deteksi dini anomali proyek, potensi mangkrak, dan peringatan keterlambatan.</p>
        </div>
        <button class="btn-primary bg-red-600 hover:bg-red-700 border-none px-6">Unduh Laporan Risiko</button>
    </div>

    {{-- Highlight / Alert --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="text-sm">
            <p class="font-bold mb-1">Informasi Modul</p>
            <p>Halaman EWS ini adalah antarmuka untuk mendeteksi proyek yang masuk dalam zona merah (kritis). Logika algoritma akan membaca perbandingan jadwal, realisasi anggaran, dan pelaporan lapangan untuk men-trigger *alert* secara otomatis.</p>
        </div>
    </div>

    {{-- Alert Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        @forelse($criticalAlerts as $alert)
        {{-- Critical Alert --}}
        <div class="bg-white rounded-xl shadow-card border border-red-200 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-3">
                <span class="flex h-3 w-3 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            </div>
            <div class="p-5 border-b border-gray-100 bg-red-50/30">
                <div class="flex items-center gap-2 text-red-600 font-bold mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Tingkat Kritis: SANGAT TINGGI
                </div>
                <h3 class="text-lg font-bold text-gray-800">{{ $alert->kegiatan->nama_kegiatan }}</h3>
                <p class="text-xs text-gray-500">Desa {{ $alert->kegiatan->desa->nama }}, Kecamatan {{ $alert->kegiatan->desa->kecamatan->nama }}</p>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Sisa Waktu Kontrak</p>
                        <p class="font-bold {{ $alert->days_left < 0 ? 'text-red-600' : 'text-orange-600' }}">
                            {{ $alert->days_left < 0 ? 'Terlewat ' . abs($alert->days_left) . ' Hari' : $alert->days_left . ' Hari Lagi' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Target vs Realisasi Fisik</p>
                        <p class="font-bold text-gray-800">
                            {{ $alert->kegiatan->persentase_keuangan }}% vs <span class="text-red-600">{{ $alert->kegiatan->progres_fisik }}%</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Pencairan Dana</p>
                        <p class="font-bold text-gray-800">Telah cair {{ $alert->kegiatan->persentase_keuangan }}%</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Potensi Risiko</p>
                        <p class="font-bold text-red-600">{{ $alert->reason }}</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 flex gap-2">
                    <a href="{{ route('kegiatan.show', $alert->kegiatan->id) }}" class="btn-primary py-1.5 px-4 text-xs bg-red-600 hover:bg-red-700 border-none text-white text-center">Detail Kegiatan</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 p-8 bg-green-50 border border-green-200 rounded-xl text-center">
            <svg class="w-12 h-12 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-lg font-bold text-green-800">Tidak ada Peringatan Kritis</h3>
            <p class="text-sm text-green-600 mt-1">Semua kegiatan terpantau aman dan berjalan sesuai jadwal.</p>
        </div>
        @endforelse

        @foreach($mediumAlerts as $alert)
        {{-- Medium Alert --}}
        <div class="bg-white rounded-xl shadow-card border border-orange-200 overflow-hidden relative">
            <div class="p-5 border-b border-gray-100 bg-orange-50/30">
                <div class="flex items-center gap-2 text-orange-600 font-bold mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Tingkat Kritis: SEDANG
                </div>
                <h3 class="text-lg font-bold text-gray-800">{{ $alert->kegiatan->nama_kegiatan }}</h3>
                <p class="text-xs text-gray-500">Desa {{ $alert->kegiatan->desa->nama }}, Kecamatan {{ $alert->kegiatan->desa->kecamatan->nama }}</p>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Sisa Waktu Kontrak</p>
                        <p class="font-bold text-orange-600">{{ $alert->days_left }} Hari Lagi</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Progres Saat Ini</p>
                        <p class="font-bold text-orange-600">{{ $alert->kegiatan->progres_fisik }}%</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-0.5">Catatan Sistem</p>
                        <p class="font-medium text-gray-700 text-xs">{{ $alert->reason }}. Pencairan mencapai {{ $alert->kegiatan->persentase_keuangan }}% namun fisik baru {{ $alert->kegiatan->progres_fisik }}%.</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 flex gap-2">
                    <a href="{{ route('kegiatan.show', $alert->kegiatan->id) }}" class="btn-primary py-1.5 px-4 text-xs bg-orange-600 hover:bg-orange-700 border-none text-white text-center">Notifikasi Teguran / Detail</a>
                </div>
            </div>
        </div>
        @endforeach
</div>
@endsection
