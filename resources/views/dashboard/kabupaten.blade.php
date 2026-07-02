@extends('layouts.app')

@section('title', 'Dashboard Kabupaten')

@section('content')
<div class="space-y-6">

    {{-- Page Header & Filters --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Dashboard Eksekutif Kabupaten
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Selamat datang, {{ $user->name }}, Berikut ringkasan monitoring pembangunan tingkat Kabupaten.
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
                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <select name="kecamatan_id" class="bg-transparent border-none text-sm font-medium focus:ring-0 py-0 pl-0 pr-6 cursor-pointer" onchange="this.form.submit()">
                        <option value="">Seluruh Kabupaten</option>
                        @foreach($allKecamatans as $kec)
                            <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        {{-- Total Kecamatan --}}
        <div class="stat-card flex items-center gap-4 border-l-4 border-l-simpatik-600">
            <div class="stat-card-icon bg-simpatik-100 text-simpatik-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Kecamatan</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalKecamatan }}</p>
            </div>
        </div>

        {{-- Total Desa --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-blue-100 text-blue-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Desa</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalDesa }}</p>
            </div>
        </div>

        {{-- Total Kegiatan --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-purple-100 text-purple-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Kegiatan</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalKegiatan }}</p>
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
            </div>
        </div>

        {{-- Realisasi Fisik --}}
        <div class="stat-card flex items-center gap-4">
            <div class="stat-card-icon bg-simpatik-100 text-simpatik-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Fisik Kabupaten</p>
                <p class="text-xl font-bold text-gray-800">{{ $avgProgresFisik }}%</p>
            </div>
        </div>

        {{-- Realisasi Keuangan --}}
        <div class="stat-card flex items-center gap-4 bg-simpatik-50">
            <div class="stat-card-icon bg-simpatik-800 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Keuangan Kabupaten</p>
                <p class="text-xl font-bold text-gray-800">{{ $avgRealisasiKeuangan }}%</p>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        {{-- Grafik Realisasi Fisik --}}
        <div class="chart-card col-span-1 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-800">Kurva-S Realisasi Kabupaten</h3>
                <span class="badge badge-success">{{ $avgProgresFisik }}%</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="progresChart"></canvas>
            </div>
        </div>

        {{-- Ranking Kinerja Kecamatan --}}
        <div class="chart-card col-span-1 lg:col-span-2">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-2">
                <h3 class="text-sm font-bold text-gray-800">Kecamatan Terbaik (Kinerja Pembangunan)</h3>
                <a href="#" class="text-[10px] text-simpatik-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @foreach($rankingKecamatan as $index => $kec)
                <div class="flex items-center justify-between text-sm group">
                    <div class="flex items-center gap-3 w-1/3">
                        <span class="w-5 font-bold {{ $index < 3 ? 'text-simpatik-600' : 'text-gray-400' }}">#{{ $index + 1 }}</span>
                        <div class="leading-tight">
                            <span class="font-bold text-gray-700 truncate block">Kec. {{ $kec['nama'] }}</span>
                            <span class="text-[10px] text-gray-500">{{ $kec['total_desa'] }} Desa · {{ $kec['total_kegiatan'] }} Kegiatan</span>
                        </div>
                    </div>
                    <div class="flex-1 mx-3 flex items-center gap-2">
                        <div class="progress-bar flex-1 h-2 relative bg-gray-100 rounded-full overflow-hidden">
                            <div class="progress-bar-fill absolute top-0 left-0 bottom-0 {{ $kec['rata_rata_progres'] < 50 ? 'bg-yellow-400' : 'bg-simpatik-500' }}" style="width: {{ $kec['rata_rata_progres'] }}%"></div>
                        </div>
                    </div>
                    <span class="font-bold text-xs w-10 text-right {{ $kec['rata_rata_progres'] < 50 ? 'text-yellow-600' : 'text-simpatik-600' }}">{{ $kec['rata_rata_progres'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Map Row --}}
    <div class="chart-card h-[400px] relative overflow-hidden">
        <div class="absolute top-4 left-4 z-[400] bg-white/95 backdrop-blur px-4 py-3 rounded-xl shadow-lg border border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                Peta Pembangunan Kabupaten
            </h3>
            <p class="text-[10px] text-gray-500 mt-1 mb-3">Sebaran titik kegiatan infrastruktur</p>
            <div class="flex flex-col gap-2 text-xs font-medium">
                <div class="flex justify-between items-center bg-gray-50 px-2 py-1.5 rounded">
                    <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-simpatik-600"></div> Selesai</span>
                    <span class="text-gray-600">{{ $statusCounts['selesai'] }}</span>
                </div>
                <div class="flex justify-between items-center bg-gray-50 px-2 py-1.5 rounded">
                    <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div> Berjalan</span>
                    <span class="text-gray-600">{{ $statusCounts['berjalan'] }}</span>
                </div>
                <div class="flex justify-between items-center bg-gray-50 px-2 py-1.5 rounded">
                    <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></div> Terlambat</span>
                    <span class="text-red-600">{{ $statusCounts['terlambat'] }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('gis.index') }}" class="absolute bottom-4 right-4 z-[400] text-xs text-white bg-simpatik-800 px-4 py-2 rounded-lg shadow-lg font-bold hover:bg-simpatik-900 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            Buka Layar Penuh
        </a>
        <div id="map" class="w-full h-full rounded-lg z-0"></div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        initCharts();
        initMap();
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (window.Alpine) {
            setTimeout(() => {
                initCharts();
                initMap();
            }, 100);
        }
    });

    function initCharts() {
        const monthlyData = @json($monthlyData);

        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.color = '#6b7280';
        Chart.defaults.scale.grid.color = '#f3f4f6';

        const ctxProgres = document.getElementById('progresChart');
        if (ctxProgres) {
            new Chart(ctxProgres, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Realisasi (%)',
                        data: monthlyData,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#166534',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                        },
                        x: {
                            grid: { display: false }
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
        let centerLat = -7.0219;
        let centerLng = 107.5279;
        
        const map = L.map('map', { zoomControl: false }).setView([centerLat, centerLng], 11);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: 'OpenStreetMap',
            maxZoom: 18
        }).addTo(map);

        const colors = {
            'selesai': '#16a34a',
            'berjalan': '#facc15',
            'terlambat': '#ef4444',
            'belum_mulai': '#9ca3af'
        };

        mapData.forEach(item => {
            if(item.lat && item.lng) {
                const color = colors[item.status] || colors['belum_mulai'];
                const svgIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: ${color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });

                L.marker([item.lat, item.lng], { icon: svgIcon })
                 .bindPopup(`
                    <div class="text-xs font-poppins p-1">
                        <p class="font-bold mb-1">${item.nama}</p>
                        <p class="text-gray-500 mb-1">Desa ${item.desa}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex-1 bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                <div class="h-full bg-simpatik-500" style="width: ${item.progres}%"></div>
                            </div>
                            <span class="font-bold text-[10px]">${item.progres}%</span>
                        </div>
                    </div>
                 `)
                 .addTo(map);
            }
        });
    }
</script>
@endpush
