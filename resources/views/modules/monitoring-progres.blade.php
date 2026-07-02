@extends('layouts.app')

@section('title', 'Monitoring Progres Konstruksi')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Monitoring Progres Fisik
            </h1>
            <p class="text-sm text-gray-500 mt-1">Pemantauan deviasi antara kurva-S rencana vs realisasi aktual di lapangan.</p>
        </div>
        <div class="flex gap-2">
            <select class="form-select text-sm py-2">
                <option>Semua Proyek Aktif</option>
                <option>Proyek Terlambat</option>
            </select>
            <button class="btn-primary py-2 px-4 shrink-0">Export Laporan</button>
        </div>
    </div>

    {{-- Highlight / Alert --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="text-sm">
            <p class="font-bold mb-1">Informasi Modul</p>
            <p>Halaman ini menampilkan simulasi agregat (*mockup*) dari fitur Monitoring Progres. Di masa mendatang, data ini akan ditarik secara *real-time* dari aplikasi *mobile* yang digunakan oleh konsultan pengawas di lapangan.</p>
        </div>
    </div>

    {{-- Mockup Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- S-Curve Chart Placeholder --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800">Kurva-S Agregat Kabupaten</h3>
                <div class="flex gap-4 text-xs font-medium">
                    <span class="flex items-center gap-1.5"><div class="w-3 h-3 bg-gray-300 rounded-full"></div> Rencana</span>
                    <span class="flex items-center gap-1.5"><div class="w-3 h-3 bg-simpatik-600 rounded-full"></div> Realisasi</span>
                </div>
            </div>
            
            {{-- Fake Chart Area --}}
            <div class="h-64 border-l-2 border-b-2 border-gray-200 relative pb-6">
                {{-- Y axis labels --}}
                <div class="absolute -left-8 top-0 text-[10px] text-gray-400">100%</div>
                <div class="absolute -left-6 top-1/2 text-[10px] text-gray-400">50%</div>
                <div class="absolute -left-4 bottom-4 text-[10px] text-gray-400">0%</div>
                
                {{-- X axis labels --}}
                <div class="absolute bottom-0 left-[10%] text-[10px] text-gray-400">Jan</div>
                <div class="absolute bottom-0 left-[30%] text-[10px] text-gray-400">Mar</div>
                <div class="absolute bottom-0 left-[50%] text-[10px] text-gray-400">Mei</div>
                <div class="absolute bottom-0 left-[70%] text-[10px] text-gray-400">Jul</div>
                <div class="absolute bottom-0 left-[90%] text-[10px] text-gray-400">Sep</div>

                {{-- Chart lines (SVG) --}}
                <svg class="w-full h-full overflow-visible" preserveAspectRatio="none">
                    {{-- Rencana (Grey dashed) --}}
                    <path d="M 0 200 C 50 190, 150 150, 250 100 S 400 20, 500 0" fill="none" stroke="#d1d5db" stroke-width="3" stroke-dasharray="6,6" vector-effect="non-scaling-stroke"></path>
                    {{-- Realisasi (Green solid) --}}
                    <path d="M 0 200 C 60 195, 140 170, 220 120" fill="none" stroke="#16a34a" stroke-width="4" vector-effect="non-scaling-stroke"></path>
                </svg>
            </div>
            <p class="text-center text-xs text-gray-500 mt-4">Simulasi perbandingan akumulasi target vs capaian proyek tahun anggaran berjalan.</p>
        </div>

        {{-- Progress Leaderboard --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Status Keterlambatan Tertinggi</h3>
            <div class="space-y-4">
                {{-- Item 1 --}}
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold text-gray-700">Pembangunan Irigasi</span>
                        <span class="text-red-600 font-bold">Deviasi -15%</span>
                    </div>
                    <div class="text-[10px] text-gray-500 mb-2">Desa Tarumajaya · Target 60% · Realisasi 45%</div>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden flex">
                        <div class="h-full bg-simpatik-500" style="width: 45%"></div>
                        <div class="h-full bg-red-400 opacity-50" style="width: 15%"></div>
                    </div>
                </div>
                {{-- Item 2 --}}
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold text-gray-700">Rehabilitasi Posyandu</span>
                        <span class="text-red-600 font-bold">Deviasi -8%</span>
                    </div>
                    <div class="text-[10px] text-gray-500 mb-2">Desa Sayati · Target 30% · Realisasi 22%</div>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden flex">
                        <div class="h-full bg-simpatik-500" style="width: 22%"></div>
                        <div class="h-full bg-red-400 opacity-50" style="width: 8%"></div>
                    </div>
                </div>
                {{-- Item 3 --}}
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold text-gray-700">Pengaspalan Jalan Lingkungan</span>
                        <span class="text-yellow-600 font-bold">Deviasi -2%</span>
                    </div>
                    <div class="text-[10px] text-gray-500 mb-2">Desa Pamekaran · Target 80% · Realisasi 78%</div>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden flex">
                        <div class="h-full bg-simpatik-500" style="width: 78%"></div>
                        <div class="h-full bg-yellow-400 opacity-50" style="width: 2%"></div>
                    </div>
                </div>
            </div>
            <button class="w-full mt-6 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">Lihat Semua Proyek</button>
        </div>

    </div>
</div>
@endsection
