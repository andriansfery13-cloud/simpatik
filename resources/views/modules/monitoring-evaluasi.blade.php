@extends('layouts.app')

@section('title', 'Monitoring & Evaluasi Indikator Kinerja')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Monitoring & Evaluasi (MONEV)
            </h1>
            <p class="text-sm text-gray-500 mt-1">Evaluasi Indikator Kinerja Utama (IKU) dan efektivitas pelaksanaan program desa.</p>
        </div>
        <div class="flex gap-2">
            <select class="form-select text-sm py-2">
                <option>Tahun 2024</option>
                <option>Tahun 2023</option>
            </select>
        </div>
    </div>

    {{-- Highlight / Alert --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="text-sm">
            <p class="font-bold mb-1">Informasi Modul</p>
            <p>Halaman ini merupakan *dashboard* simulasi untuk evaluasi pasca-pembangunan. Modul ini akan digunakan oleh BPD dan Inspektorat untuk menilai asas kemanfaatan dari setiap infrastruktur yang telah terbangun.</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-simpatik-50 text-simpatik-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Efisiensi Anggaran</h3>
            </div>
            <p class="text-2xl font-bold text-gray-800">92.5%</p>
            <p class="text-[10px] text-simpatik-600 font-medium mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                +2.1% dari target
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Ketepatan Waktu</h3>
            </div>
            <p class="text-2xl font-bold text-gray-800">78.0%</p>
            <p class="text-[10px] text-red-500 font-medium mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                -4.5% dari target
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Asas Kemanfaatan</h3>
            </div>
            <p class="text-2xl font-bold text-gray-800">85/100</p>
            <p class="text-[10px] text-gray-500 mt-1">Skor IKM Masyarakat</p>
        </div>
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Temuan BPK/Inspektorat</h3>
            </div>
            <p class="text-2xl font-bold text-gray-800">3 <span class="text-sm font-normal text-gray-500">Kasus</span></p>
            <p class="text-[10px] text-orange-600 font-medium mt-1">Status: Perbaikan Administrasi</p>
        </div>
    </div>

    {{-- Detail Evaluasi Tabel --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Hasil Evaluasi Proyek Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Desa</th>
                        <th>Nama Kegiatan</th>
                        <th class="text-center">Kesesuaian RAB</th>
                        <th class="text-center">Kualitas Fisik</th>
                        <th class="text-center">Aspek Manfaat</th>
                        <th class="text-center">Skor Akhir</th>
                        <th>Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    <tr>
                        <td class="font-medium">Desa Ciwidey</td>
                        <td>Pembangunan Jembatan Gantung</td>
                        <td class="text-center"><span class="badge badge-success">Sesuai (A)</span></td>
                        <td class="text-center"><span class="badge badge-success">Baik (A)</span></td>
                        <td class="text-center"><span class="badge badge-info">Cukup (B)</span></td>
                        <td class="text-center font-bold text-simpatik-600">88.5</td>
                        <td class="text-xs text-gray-500">Perlu pemeliharaan rutin jembatan setiap 6 bulan.</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Desa Soreang</td>
                        <td>Pengecoran Jalan Usaha Tani</td>
                        <td class="text-center"><span class="badge badge-warning">Deviasi 5% (B)</span></td>
                        <td class="text-center"><span class="badge badge-info">Cukup (B)</span></td>
                        <td class="text-center"><span class="badge badge-success">Sangat Baik (A)</span></td>
                        <td class="text-center font-bold text-yellow-600">76.0</td>
                        <td class="text-xs text-gray-500">Terdapat retak rambut pada segmen 2, perbaikan masih dalam masa pemeliharaan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
