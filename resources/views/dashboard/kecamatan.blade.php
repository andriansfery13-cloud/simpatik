@extends('layouts.app')

@section('title', $user->isDesa() ? 'Dashboard Desa' : 'Dashboard Kecamatan')

@section('content')
<div class="space-y-6">

    {{-- Page Header & Filters --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                @if($user->isDesa() && $user->desa)
                    Dashboard Desa {{ $user->desa->nama }}
                @else
                    Dashboard {{ $kecamatan->nama ? 'Kecamatan ' . $kecamatan->nama : 'Kabupaten' }}
                @endif
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Selamat datang, {{ $user->name }}, Berikut ringkasan monitoring pembangunan desa hari ini.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-3">
                <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200 px-3 py-1.5">
                    <span class="text-xs text-gray-500 mr-2">Tahun</span>
                    <select name="tahun" class="bg-transparent border-none text-sm font-medium focus:ring-0 py-0 pl-0 pr-6 cursor-pointer" onchange="this.form.submit()">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200 px-3 py-1.5">
                    <span class="text-xs text-gray-500 mr-2">Bulan</span>
                    <select name="periode" class="bg-transparent border-none text-sm font-medium focus:ring-0 py-0 pl-0 pr-6 cursor-pointer" onchange="this.form.submit()">
                        <option value="2026-06" {{ $periode == '2026-06' ? 'selected' : '' }}>Juni</option>
                        <option value="2026-05" {{ $periode == '2026-05' ? 'selected' : '' }}>Mei</option>
                        <option value="2026-04" {{ $periode == '2026-04' ? 'selected' : '' }}>April</option>
                        <option value="2026-03" {{ $periode == '2026-03' ? 'selected' : '' }}>Maret</option>
                    </select>
                </div>
                <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200 px-3 py-1.5">
                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <select name="desa_id" class="bg-transparent border-none text-sm font-medium focus:ring-0 py-0 pl-0 pr-6 {{ $user->isDesa() ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer' }}" onchange="this.form.submit()" {{ $user->isDesa() ? 'disabled' : '' }}>
                        @if(!$user->isDesa())
                            <option value="">Semua Desa</option>
                        @endif
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ $selectedDesaId == $desa->id ? 'selected' : '' }}>{{ $desa->nama }}</option>
                        @endforeach
                    </select>
                    @if($user->isDesa())
                        <input type="hidden" name="desa_id" value="{{ $selectedDesaId }}">
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        {{-- Total Desa --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-simpatik-100 text-simpatik-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Desa</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalDesa }}</p>
                <p class="text-[10px] text-gray-400">Desa</p>
            </div>
        </div>

        {{-- Total Kegiatan --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-blue-100 text-blue-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Kegiatan</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalKegiatan }}</p>
                <p class="text-[10px] text-gray-400">Kegiatan</p>
            </div>
        </div>

        {{-- Total Anggaran --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-yellow-100 text-yellow-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Anggaran</p>
                <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalAnggaran / 1000000000, 2, ',', '.') }} M</p>
                <p class="text-[10px] text-gray-400">Total Pagu</p>
            </div>
        </div>

        {{-- Realisasi Fisik --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-simpatik-100 text-simpatik-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Realisasi Fisik</p>
                <p class="text-xl font-bold text-gray-800">{{ $avgProgresFisik }}%</p>
                <p class="text-[10px] text-gray-400">Rata-rata</p>
            </div>
        </div>

        {{-- Realisasi Keuangan --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-simpatik-800 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Realisasi Keuangan</p>
                <p class="text-xl font-bold text-gray-800">{{ $avgRealisasiKeuangan }}%</p>
                <p class="text-[10px] text-gray-400">Rata-rata</p>
            </div>
        </div>

        {{-- Kegiatan Terlambat --}}
        <div class="stat-card flex items-center gap-4 border-red-100 bg-red-50/30">
            <div class="stat-card-icon bg-red-100 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Kegiatan Terlambat</p>
                <p class="text-xl font-bold text-red-600">{{ $kegiatanTerlambat }}</p>
                <p class="text-[10px] text-gray-400">Kegiatan</p>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        {{-- Grafik Realisasi Fisik --}}
        <div class="chart-card col-span-1 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-800">Grafik Realisasi Fisik</h3>
                <span class="badge badge-success">{{ $avgProgresFisik }}%</span>
            </div>
            <div class="relative h-48 w-full">
                <canvas id="progresChart"></canvas>
            </div>
        </div>

        {{-- Status Kegiatan --}}
        <div class="chart-card col-span-1">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Status Kegiatan</h3>
            <div class="relative h-48 w-full flex items-center justify-center">
                <canvas id="statusChart"></canvas>
                {{-- Custom Legend to match screenshot --}}
                <div class="absolute right-0 top-1/2 -translate-y-1/2 space-y-3 mr-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-simpatik-600"></div>
                        <div class="text-xs">
                            <p class="font-medium">Selesai</p>
                            <p class="text-gray-500">{{ $statusCounts['selesai'] }} ({{ $totalKegiatan > 0 ? round(($statusCounts['selesai']/$totalKegiatan)*100) : 0 }}%)</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="text-xs">
                            <p class="font-medium">Berjalan</p>
                            <p class="text-gray-500">{{ $statusCounts['berjalan'] }} ({{ $totalKegiatan > 0 ? round(($statusCounts['berjalan']/$totalKegiatan)*100) : 0 }}%)</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="text-xs">
                            <p class="font-medium text-red-600">Terlambat</p>
                            <p class="text-gray-500">{{ $statusCounts['terlambat'] }} ({{ $totalKegiatan > 0 ? round(($statusCounts['terlambat']/$totalKegiatan)*100) : 0 }}%)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ranking Kinerja Desa --}}
        <div class="chart-card col-span-1">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-800">Ranking Kinerja Desa</h3>
                <a href="#" class="text-[10px] text-simpatik-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4 mt-2">
                @foreach($rankingDesa as $index => $desa)
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-3">
                        <span class="w-5 font-bold text-gray-400">{{ $index + 1 }}</span>
                        <span class="font-medium text-gray-700 truncate w-24" title="{{ $desa['nama'] }}">{{ $desa['nama'] }}</span>
                    </div>
                    <div class="flex-1 mx-3 flex items-center gap-2">
                        <div class="progress-bar flex-1">
                            <div class="progress-bar-fill {{ $desa['rata_rata_progres'] < 50 ? 'bg-yellow-400' : 'bg-simpatik-500' }}" style="width: {{ $desa['rata_rata_progres'] }}%"></div>
                        </div>
                    </div>
                    <span class="font-bold text-xs {{ $desa['rata_rata_progres'] < 50 ? 'text-yellow-600' : 'text-simpatik-600' }}">{{ $desa['rata_rata_progres'] }}%</span>
                </div>
                @if(!$loop->last) <hr class="border-gray-100"> @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Map Row --}}
    <div class="chart-card h-80 relative overflow-hidden">
        <div class="absolute top-4 left-4 z-[400] bg-white/90 backdrop-blur px-3 py-2 rounded-lg shadow-sm">
            <h3 class="text-sm font-bold text-gray-800">Peta Sebaran Kegiatan</h3>
            <div class="flex items-center gap-4 mt-2 text-xs">
                <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-simpatik-600"></div> Selesai</span>
                <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-yellow-400"></div> Berjalan</span>
                <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-red-500"></div> Terlambat</span>
            </div>
        </div>
        <a href="#" class="absolute top-4 right-4 z-[400] text-xs text-simpatik-700 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-sm font-medium hover:bg-simpatik-50 transition">
            Lihat Peta >
        </a>
        <div id="map" class="w-full h-full rounded-lg z-0"></div>
    </div>

    {{-- AI Insights Row --}}
    <div x-data="{ showRekomendasiModal: false, showPrediksiModal: false, showPerhatianModal: false }" class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-simpatik-800 text-white flex items-center justify-center text-xs font-bold">AI</div>
            <h3 class="font-bold text-gray-800">AI Executive Insight</h3>
        </div>
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            
            {{-- AI Card 1: Risiko --}}
            <div class="ai-card border-red-200 bg-red-50/30">
                <div class="ai-card-header text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h4 class="font-bold text-sm">Desa Risiko Tinggi</h4>
                </div>
                <p class="text-xs text-gray-600 mb-3">
                    @if(count($aiPriority) > 0)
                        Desa {{ $aiPriority[0]['nama'] }} memiliki risiko keterlambatan tinggi. Progres fisik baru {{ $aiPriority[0]['avg_progres'] }}% sementara waktu pelaksanaan telah berjalan 70%.
                    @else
                        Tidak ada desa dengan risiko tinggi saat ini.
                    @endif
                </p>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                    RISIKO TINGGI
                </span>
            </div>

            {{-- AI Card 2: Rekomendasi --}}
            <div class="ai-card border-simpatik-200 bg-simpatik-50/30">
                <div class="ai-card-header text-simpatik-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <h4 class="font-bold text-sm">Rekomendasi AI</h4>
                </div>
                <p class="text-xs text-gray-600 mb-4">
                    Disarankan melakukan monitoring lapangan ke {{ count($aiPriority) }} desa dengan risiko tertinggi untuk memastikan kendala dan percepatan.
                </p>
                <button @click="showRekomendasiModal = true" class="w-full btn-primary py-1.5 text-xs">Lihat Rekomendasi</button>
            </div>

            {{-- AI Card 3: Prediksi --}}
            <div class="ai-card border-blue-200 bg-blue-50/30">
                <div class="ai-card-header text-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h4 class="font-bold text-sm">Prediksi Penyelesaian</h4>
                </div>
                <p class="text-xs text-gray-600 mb-4">
                    Dari {{ $kegiatanTerlambat }} kegiatan terlambat, AI memprediksi {{ min($kegiatanTerlambat, 3) }} kegiatan akan selesai tepat waktu jika progres meningkat 15% dalam 2 minggu.
                </p>
                <button @click="showPrediksiModal = true" class="w-full btn-primary bg-blue-700 hover:bg-blue-800 focus:ring-blue-500 py-1.5 text-xs">Detail Prediksi</button>
            </div>

            {{-- Perlu Perhatian List --}}
            <div class="ai-card col-span-1 lg:col-span-2">
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2">
                    <div class="flex items-center gap-2 text-yellow-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <h4 class="font-bold text-sm">Kegiatan Perlu Perhatian</h4>
                    </div>
                    <button type="button" @click="showPerhatianModal = true" class="text-[10px] text-simpatik-600 hover:underline">Lihat Semua</button>
                </div>
                <div class="space-y-3">
                    @forelse($kegiatanPerhatian->take(3) as $k)
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div>
                            <p class="text-xs font-medium text-gray-800">{{ $k->nama_kegiatan }}</p>
                            <p class="text-[10px] text-gray-500">Progres {{ $k->progres_fisik }}% - {{ $k->status == 'terlambat' ? 'Terlambat ' . abs(now()->diffInDays($k->tanggal_selesai)) . ' hari' : 'Progres lambat' }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-500">Semua kegiatan berjalan sesuai rencana.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Modals for AI Insights --}}
        
        {{-- Rekomendasi Modal --}}
        <div x-show="showRekomendasiModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showRekomendasiModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showRekomendasiModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showRekomendasiModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-simpatik-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Rekomendasi AI Executive</h3>
                                <div class="mt-4 space-y-4 text-sm text-gray-600">
                                    <p>Berdasarkan analisis data algoritma SIMPATIK, berikut adalah langkah mitigasi yang disarankan:</p>
                                    <ul class="list-disc pl-5 space-y-2">
                                        <li><strong class="text-gray-800">Prioritaskan Monitoring:</strong> Jadwalkan kunjungan lapangan segera ke desa-desa dengan status risiko tinggi untuk mengidentifikasi "bottleneck".</li>
                                        <li><strong class="text-gray-800">Evaluasi Pencairan Dana:</strong> Tinjau kembali syarat pencairan termin selanjutnya bagi kegiatan yang mengalami deviasi progres fisik lebih dari 20%.</li>
                                        <li><strong class="text-gray-800">Bantuan Teknis:</strong> Kerahkan tenaga ahli pendamping desa ke lokasi proyek dengan pelaporan kualitas rendah.</li>
                                        <li><strong class="text-gray-800">Peringatan Tertulis:</strong> Terbitkan surat peringatan otomatis kepada pelaksana kegiatan yang terlambat lebih dari 14 hari.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showRekomendasiModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Prediksi Modal --}}
        <div x-show="showPrediksiModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPrediksiModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showPrediksiModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showPrediksiModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Detail Prediksi AI</h3>
                                <div class="mt-4 space-y-4 text-sm text-gray-600">
                                    <p>Berdasarkan tren capaian bulanan dan historis cuaca, AI memproyeksikan beberapa hal berikut:</p>
                                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                        <h4 class="font-bold text-blue-800 mb-2">Skenario Optimis (Peningkatan Progres 15%/minggu)</h4>
                                        <p class="text-blue-700 mb-2">Terdapat peluang <strong>{{ min($kegiatanTerlambat, 3) }} kegiatan</strong> yang berstatus "Terlambat" dapat mengejar ketertinggalan dan selesai pada akhir bulan ini.</p>
                                    </div>
                                    <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                                        <h4 class="font-bold text-red-800 mb-2">Skenario Pesimis (Tanpa Intervensi)</h4>
                                        <p class="text-red-700">Jika tren saat ini dibiarkan, kemungkinan jumlah kegiatan "Terlambat" akan bertambah <strong>2 kegiatan lagi</strong> di bulan depan karena faktor cuaca ekstrem yang diprediksi BMKG.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showPrediksiModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Perhatian Modal --}}
        <div x-show="showPerhatianModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPerhatianModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showPerhatianModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showPerhatianModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Daftar Lengkap Kegiatan Perlu Perhatian</h3>
                                <div class="mt-4 max-h-[60vh] overflow-y-auto pr-2">
                                    <div class="space-y-3">
                                        @forelse($kegiatanPerhatian as $k)
                                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="shrink-0 mt-0.5">
                                                @if($k->status == 'terlambat')
                                                    <span class="inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                                                @else
                                                    <span class="inline-flex h-2 w-2 rounded-full bg-yellow-500"></span>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <a href="{{ route('kegiatan.show', $k) }}" class="text-sm font-bold text-gray-800 hover:text-simpatik-600 transition">{{ $k->nama_kegiatan }}</a>
                                                <p class="text-xs text-gray-500 mt-1">Desa: {{ $k->desa->nama }} | Progres: {{ $k->progres_fisik }}%</p>
                                                <div class="mt-2 text-xs font-medium {{ $k->status == 'terlambat' ? 'text-red-600 bg-red-50' : 'text-yellow-600 bg-yellow-50' }} p-2 rounded inline-block">
                                                    {{ $k->status == 'terlambat' ? 'Terlambat ' . abs(now()->diffInDays($k->tanggal_selesai)) . ' hari dari target selesai.' : 'Progres sangat lambat, indikasi keterlambatan.' }}
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('kegiatan.show', $k) }}" class="btn-secondary py-1 px-3 text-xs">Pantau</a>
                                            </div>
                                        </div>
                                        @empty
                                        <p class="text-sm text-gray-500 text-center py-4">Semua kegiatan berjalan sesuai rencana saat ini.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showPerhatianModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Bottom Feature Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        
        {{-- Dokumentasi --}}
        <div class="feature-card lg:col-span-1 border-t-4 border-t-simpatik-500 flex flex-col h-full">
            <div class="flex items-center gap-2 mb-2 text-simpatik-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h4 class="font-bold text-sm text-gray-800">Dokumentasi Foto</h4>
            </div>
            <p class="text-[10px] text-gray-500 mb-3 flex-1">Sebelum, proses, sesudah pekerjaan dengan lokasi & waktu otomatis.</p>
            <div class="flex gap-1">
                <div class="w-1/3 aspect-square bg-gray-200 rounded object-cover overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1584483766114-2ca8b476fb87?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-[8px] text-center py-0.5">Sebelum</div>
                </div>
                <div class="w-1/3 aspect-square bg-gray-200 rounded object-cover overflow-hidden relative border-2 border-yellow-400">
                    <img src="https://images.unsplash.com/photo-1541888081622-c2830f368940?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-[8px] text-center py-0.5">Proses</div>
                </div>
                <div class="w-1/3 aspect-square bg-gray-200 rounded object-cover overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1499597148590-7db0a0fc09a9?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-[8px] text-center py-0.5">Sesudah</div>
                </div>
            </div>
        </div>

        {{-- Monitoring Lapangan --}}
        <div class="feature-card lg:col-span-1 border-t-4 border-t-blue-500 flex flex-col h-full">
            <div class="flex items-center gap-2 mb-2 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <h4 class="font-bold text-sm text-gray-800">Monitoring Lapangan</h4>
            </div>
            <p class="text-[10px] text-gray-500 mb-3">Input hasil monitoring, checklist, catatan dan foto GPS.</p>
            <div class="mt-auto space-y-2">
                <div class="bg-gray-50 p-2 rounded border border-gray-100 flex items-start gap-2">
                    <svg class="w-3 h-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    <div>
                        <p class="text-[9px] text-gray-500">Lokasi</p>
                        <p class="text-[10px] font-medium">-7.0123, 107.5890</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-100 flex items-start gap-2">
                    <svg class="w-3 h-3 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-[9px] text-gray-500">Hasil Monitoring</p>
                        <p class="text-[10px] font-medium text-green-700">Sesuai</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Realisasi Anggaran --}}
        <div class="feature-card lg:col-span-1 border-t-4 border-t-yellow-500 flex flex-col h-full">
            <div class="flex items-center gap-2 mb-2 text-yellow-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h4 class="font-bold text-sm text-gray-800">Realisasi Anggaran</h4>
            </div>
            <p class="text-[10px] text-gray-500 mb-3">Monitoring penggunaan anggaran secara transparan.</p>
            <div class="mt-auto space-y-3">
                <div>
                    <p class="text-[9px] text-gray-500">Pagu Anggaran</p>
                    <p class="text-sm font-bold text-gray-800">Rp 250.000.000</p>
                </div>
                <div>
                    <div class="flex justify-between items-end mb-1">
                        <p class="text-[9px] text-gray-500">Realisasi</p>
                        <p class="text-[10px] font-bold text-simpatik-600">67,30%</p>
                    </div>
                    <p class="text-xs font-bold text-gray-800 mb-1">Rp 168.250.000</p>
                    <div class="progress-bar h-1.5">
                        <div class="progress-bar-fill bg-simpatik-500 h-1.5" style="width: 67.3%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transparansi Publik --}}
        <div class="feature-card lg:col-span-1 border-t-4 border-t-purple-500 flex flex-col h-full">
            <div class="flex items-center gap-2 mb-2 text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <h4 class="font-bold text-sm text-gray-800">Transparansi Publik</h4>
            </div>
            <p class="text-[10px] text-gray-500 mb-3 flex-1">Informasi kegiatan dapat diakses masyarakat secara terbuka.</p>
            <div class="bg-gray-50 p-2 rounded border border-gray-100">
                <p class="text-[10px] font-bold text-gray-800">Rabat Beton</p>
                <p class="text-[9px] text-gray-500 mb-2">Desa Sukamaju</p>
                <div class="flex justify-between text-[9px] mb-1">
                    <span>Anggaran</span>
                    <span class="font-medium">60%</span>
                </div>
                <div class="flex justify-between text-[9px]">
                    <span>Progres</span>
                    <span class="font-medium text-simpatik-600">60%</span>
                </div>
                <a href="{{ route('transparansi.index') }}" class="block text-center mt-2 text-[9px] text-simpatik-600 font-medium hover:underline">Lihat Detail</a>
            </div>
        </div>

        {{-- AI Analytics + Mobile --}}
        <div class="feature-card lg:col-span-2 border-t-4 border-t-indigo-500 relative overflow-hidden bg-gradient-to-br from-white to-indigo-50">
            <div class="flex gap-4 h-full relative z-10">
                <div class="flex-1 flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-5 h-5 rounded bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold">AI</div>
                        <h4 class="font-bold text-sm text-gray-800">AI Analytics</h4>
                    </div>
                    <p class="text-[10px] text-gray-600 mb-2">Analisis risiko, prediksi, rekomendasi dan insight berbasis AI.</p>
                    <div class="mt-auto opacity-70">
                        <svg viewBox="0 0 100 30" class="w-full h-8 stroke-indigo-400 fill-none" stroke-width="2">
                            <path d="M0,25 Q10,5 20,20 T40,10 T60,25 T80,5 T100,20"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="w-32 flex flex-col justify-center border-l border-indigo-100 pl-4">
                    <h4 class="font-bold text-[11px] text-gray-800 mb-1">SIMPATIK Mobile</h4>
                    <p class="text-[9px] text-gray-500 mb-2 leading-tight">Monitoring lebih mudah langsung dari lapangan melalui aplikasi mobile.</p>
                    <div class="text-[9px] font-medium text-indigo-600 mb-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Scan QR Code
                    </div>
                    <div class="w-16 h-16 bg-white p-1 rounded shadow-sm border border-gray-200">
                        <!-- Mock QR Code -->
                        <div class="w-full h-full bg-[url('https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg')] bg-cover bg-center opacity-80"></div>
                    </div>
                    <a href="#" class="mt-1 text-[8px] text-center text-gray-500 hover:text-indigo-600">Unduh Aplikasi</a>
                </div>
            </div>
            <!-- Mock Phone Outline in background -->
            <div class="absolute -right-4 -bottom-8 w-24 h-48 border-[3px] border-gray-300 rounded-3xl opacity-20 transform rotate-12 z-0"></div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Init Charts when Alpine is ready
        initCharts();
        initMap();
    });

    // Fallback if Alpine is already initialized
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Alpine) {
            setTimeout(() => {
                initCharts();
                initMap();
            }, 100);
        }
    });

    function initCharts() {
        // Data from controller
        const monthlyData = @json($monthlyData);
        const statusCounts = @json($statusCounts);

        // Chart.js Default Configs
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.color = '#6b7280';
        Chart.defaults.scale.grid.color = '#f3f4f6';

        // 1. Progres Fisik Line Chart
        const ctxProgres = document.getElementById('progresChart');
        if (ctxProgres) {
            new Chart(ctxProgres, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Rata-rata Progres (%)',
                        data: monthlyData,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#166534',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                },
                                stepSize: 25,
                                font: { size: 10 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // 2. Status Kegiatan Donut Chart
        const ctxStatus = document.getElementById('statusChart');
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Berjalan', 'Terlambat', 'Belum Mulai'],
                    datasets: [{
                        data: [
                            statusCounts.selesai,
                            statusCounts.berjalan,
                            statusCounts.terlambat,
                            statusCounts.belum_mulai
                        ],
                        backgroundColor: [
                            '#16a34a', // green (selesai)
                            '#facc15', // yellow (berjalan)
                            '#ef4444', // red (terlambat)
                            '#9ca3af'  // gray (belum mulai)
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    layout: {
                        padding: { right: 120 } // Space for custom legend
                    },
                    plugins: {
                        legend: { display: false }, // Using custom HTML legend
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.parsed + ' kegiatan';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    function initMap() {
        const mapElement = document.getElementById('map');
        if (!mapElement || !window.L) return;

        const mapData = @json($mapData);
        
        // Default center to Kabupaten Bandung
        let centerLat = -7.0219;
        let centerLng = 107.5279;
        let zoom = 11;

        // If specific kecamatan/desa is selected and has data, center there
        if (mapData.length > 0) {
            centerLat = mapData[0].lat;
            centerLng = mapData[0].lng;
            zoom = 13;
        }

        const map = L.map('map', {
            zoomControl: false // Hide default zoom to keep it clean
        }).setView([centerLat, centerLng], zoom);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Add custom markers
        const colors = {
            'selesai': '#16a34a',
            'berjalan': '#facc15',
            'terlambat': '#ef4444',
            'belum_mulai': '#9ca3af'
        };

        mapData.forEach(item => {
            if(item.lat && item.lng) {
                const color = colors[item.status] || colors['belum_mulai'];
                
                // Create custom SVG marker
                const svgIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                L.marker([item.lat, item.lng], { icon: svgIcon })
                 .bindPopup(`
                    <div class="text-xs font-poppins p-1">
                        <p class="font-bold mb-1">${item.nama}</p>
                        <p class="text-gray-500 mb-1">Desa ${item.desa}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex-1 bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                <div class="h-full bg-simpatik-500" style="width: ${item.progres}%"></div>
                            </div>
                            <span class="font-bold">${item.progres}%</span>
                        </div>
                    </div>
                 `)
                 .addTo(map);
            }
        });
    }
</script>
@endpush
