@extends('layouts.app')

@section('title', 'Kecerdasan Buatan (AI) Analytics')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-900 p-6 rounded-xl shadow-card border border-gray-800 text-white relative overflow-hidden">
        {{-- Background AI graphic --}}
        <div class="absolute right-0 top-0 opacity-20 pointer-events-none">
            <svg class="w-64 h-64 text-simpatik-400 transform translate-x-1/3 -translate-y-1/4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"></path></svg>
        </div>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                AI Analytics & Prediksi
            </h1>
            <p class="text-sm text-gray-400 mt-1">Sistem analitik berbasis Machine Learning untuk mendeteksi anomali harga dan risiko kegagalan proyek.</p>
        </div>
        <div class="relative z-10">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-simpatik-900/50 text-simpatik-400 border border-simpatik-700/50">
                <span class="w-2 h-2 rounded-full bg-simpatik-400 mr-2 animate-pulse"></span>
                AI Engine Active
            </span>
        </div>
    </div>

    {{-- Highlight / Alert --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="text-sm">
            <p class="font-bold mb-1">Informasi Modul</p>
            <p>Halaman ini menampilkan gambaran fitur *Artificial Intelligence*. Ke depannya, AI akan menganalisis kesesuaian harga satuan bahan bangunan di RAB dengan standar harga kabupaten (SSH), mendeteksi anomali foto progres, dan memprediksi tren keberhasilan desa.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Anomaly Detection Card --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Deteksi Anomali RAB
                </h3>
                <span class="text-xs text-gray-500">Scan Terakhir: 2 jam lalu</span>
            </div>
            <div class="p-5 space-y-4">
                <div class="bg-red-50 border border-red-100 p-3 rounded-lg flex items-start gap-3">
                    <div class="mt-0.5 w-6 h-6 bg-red-100 text-red-600 rounded flex items-center justify-center shrink-0 font-bold text-xs">98%</div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Indikasi Mark-up Harga Semen</p>
                        <p class="text-xs text-gray-600 mt-1">Kegiatan: Pembangunan Balai RT (Desa Sayati). RAB: Rp85.000/sak vs SSH Kab: Rp65.000/sak. Deviasi ekstrim terdeteksi.</p>
                    </div>
                </div>
                <div class="bg-yellow-50 border border-yellow-100 p-3 rounded-lg flex items-start gap-3">
                    <div class="mt-0.5 w-6 h-6 bg-yellow-100 text-yellow-600 rounded flex items-center justify-center shrink-0 font-bold text-xs">84%</div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Volume Material Tidak Wajar</p>
                        <p class="text-xs text-gray-600 mt-1">Kegiatan: Pengecoran Jalan (Desa Pamekaran). Volume pasir 4x lebih tinggi dari standar teknis bina marga untuk luas 100m².</p>
                    </div>
                </div>
                <button class="w-full py-2 border border-gray-200 text-gray-600 font-bold text-xs rounded-lg hover:bg-gray-50 transition-colors">Lihat Semua Anomali RAB</button>
            </div>
        </div>

        {{-- Prediction Card --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    Prediksi Risiko Keterlambatan
                </h3>
            </div>
            <div class="p-5">
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Desa Kertasari - Irigasi Skala Kecil</p>
                    <div class="flex items-center gap-4">
                        <div class="flex-1 w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full bg-red-500" style="width: 89%"></div>
                        </div>
                        <span class="text-sm font-bold text-red-600">89% Risiko Gagal</span>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">Faktor utama: Pola pencairan dana 3 tahun terakhir yang lambat, ditambah curah hujan tinggi (data BMKG terintegrasi).</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Desa Tarumajaya - Rehabilitasi Posyandu</p>
                    <div class="flex items-center gap-4">
                        <div class="flex-1 w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500" style="width: 45%"></div>
                        </div>
                        <span class="text-sm font-bold text-yellow-600">45% Risiko Terlambat</span>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">Faktor utama: Historikal pelaksana proyek sering telat 1-2 minggu dari tenggat waktu.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
