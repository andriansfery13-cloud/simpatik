@extends('layouts.app')

@section('title', 'Peta Digital GIS')

@section('content')
    <div class="h-[calc(100vh-8rem)] flex flex-col md:flex-row gap-4 animate-fade-in">

        {{-- Sidebar Filters --}}
        <div
            class="w-full md:w-80 bg-white rounded-xl shadow-card border border-gray-100 flex flex-col h-full overflow-hidden shrink-0">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <svg class="w-5 h-5 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                <h2 class="font-bold text-gray-800">Filter Pemetaan</h2>
            </div>

            <form action="{{ route('gis.index') }}" method="GET" class="p-4 flex-1 overflow-y-auto space-y-4">

                @if(auth()->user()->isKabupaten())
                    <div>
                        <label class="form-label text-xs">Kecamatan</label>
                        <select name="kecamatan_id" class="form-select w-full" onchange="this.form.submit()">
                            <option value="">Semua Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}" {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>{{ $kecamatan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if(auth()->user()->isKabupaten() || auth()->user()->isKecamatan())
                    <div>
                        <label class="form-label text-xs">Desa</label>
                        <select name="desa_id" class="form-select w-full" onchange="this.form.submit()">
                            <option value="">Semua Desa</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                                    {{ $desa->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="form-label text-xs">Status Kegiatan</label>
                    <select name="status" class="form-select w-full" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="belum_mulai" {{ request('status') == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai
                        </option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>

                <hr class="border-gray-100 my-4">

                <div class="space-y-3">
                    <p class="font-bold text-xs text-gray-700">Legenda Peta</p>
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <div class="w-3 h-3 rounded-full bg-gray-400 border border-white shadow-sm"></div> Belum Mulai
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <div class="w-3 h-3 rounded-full bg-yellow-400 border border-white shadow-sm"></div> Berjalan
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <div class="w-3 h-3 rounded-full bg-simpatik-600 border border-white shadow-sm"></div> Selesai
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <div class="w-3 h-3 rounded-full bg-red-500 border border-white shadow-sm"></div> Terlambat
                    </div>
                </div>

                <hr class="border-gray-100 my-4">

                <div class="space-y-3">
                    <p class="font-bold text-xs text-gray-700">Batas Administrasi (ArcGIS)</p>
                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" id="layer-kabupaten"
                            class="form-checkbox rounded text-purple-600 border-gray-300 focus:ring-purple-500"
                            onchange="toggleArcGISLayer('kabupaten', this.checked)">
                        Batas Kabupaten
                    </label>
                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" id="layer-kecamatan"
                            class="form-checkbox rounded text-blue-600 border-gray-300 focus:ring-blue-500"
                            onchange="toggleArcGISLayer('kecamatan', this.checked)">
                        Batas Kecamatan
                    </label>
                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" id="layer-desa"
                            class="form-checkbox rounded text-green-600 border-gray-300 focus:ring-green-500"
                            onchange="toggleArcGISLayer('desa', this.checked)">
                        Batas Desa
                    </label>
                </div>

            </form>

            <div class="p-4 border-t border-gray-100 bg-gray-50">
                <p class="text-xs text-gray-500 text-center">
                    Menampilkan <strong>{{ count($mapData) }}</strong> titik lokasi.
                </p>
            </div>
        </div>

        {{-- Map Container --}}
        <div
            class="flex-1 bg-white rounded-xl shadow-card border border-gray-100 relative overflow-hidden flex flex-col h-full z-0">
            <div id="full-map" class="w-full h-full z-0"></div>

            {{-- Floating Controls --}}
            <div class="absolute bottom-6 right-6 z-[400] flex flex-col gap-2">
                <button onclick="map.zoomIn()"
                    class="w-10 h-10 bg-white rounded-lg shadow-md border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-gray-50 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
                <button onclick="map.zoomOut()"
                    class="w-10 h-10 bg-white rounded-lg shadow-md border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-gray-50 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </button>
                <button onclick="resetView()"
                    class="w-10 h-10 bg-simpatik-600 rounded-lg shadow-md border border-simpatik-700 flex items-center justify-center text-white hover:bg-simpatik-700 focus:outline-none mt-2"
                    title="Kembali ke Pusat Wilayah">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let map;
        let markers = [];
        let defaultLat = -7.0219;
        let defaultLng = 107.5279;
        let defaultZoom = 11;

        document.addEventListener('alpine:init', () => {
            initFullMap();
        });

        // Fallback if Alpine is already initialized
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Alpine) {
                setTimeout(() => {
                    initFullMap();
                }, 100);
            }
        });

        function initFullMap() {
            const mapElement = document.getElementById('full-map');
            if (!mapElement || !window.L) return;

            const mapData = @json($mapData);

            // Calculate dynamic center if data exists
            if (mapData.length > 0) {
                let latSum = 0;
                let lngSum = 0;
                mapData.forEach(item => {
                    latSum += parseFloat(item.lat);
                    lngSum += parseFloat(item.lng);
                });
                defaultLat = latSum / mapData.length;
                defaultLng = lngSum / mapData.length;
                defaultZoom = 13;
            }

            map = L.map('full-map', {
                zoomControl: false // Using custom controls
            }).setView([defaultLat, defaultLng], defaultZoom);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            const colors = {
                'selesai': '#16a34a',
                'berjalan': '#facc15',
                'terlambat': '#ef4444',
                'belum_mulai': '#9ca3af'
            };

            const statusLabels = {
                'selesai': 'Selesai',
                'berjalan': 'Berjalan',
                'terlambat': 'Terlambat',
                'belum_mulai': 'Belum Mulai'
            };

            mapData.forEach(item => {
                if (item.lat && item.lng) {
                    const color = colors[item.status] || colors['belum_mulai'];
                    const label = statusLabels[item.status];

                    const svgIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: ${color}; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4); cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"></div>`,
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });

                    const marker = L.marker([item.lat, item.lng], { icon: svgIcon })
                        .bindPopup(`
                            <div class="text-xs font-poppins p-1 min-w-[200px]">
                                <p class="font-bold text-sm mb-1 text-gray-800">${item.nama}</p>
                                <p class="text-gray-500 mb-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    Desa ${item.desa}, Kec. ${item.kecamatan}
                                </p>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-700">Progres: ${item.progres}%</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold text-white" style="background-color: ${color}">${label}</span>
                                </div>
                                <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden mb-3">
                                    <div class="h-full" style="width: ${item.progres}%; background-color: ${color}"></div>
                                </div>
                                <a href="${item.url}" class="block w-full text-center py-1.5 bg-simpatik-50 text-simpatik-700 font-medium rounded hover:bg-simpatik-100 transition">
                                    Lihat Detail Proyek
                                </a>
                            </div>
                         `);

                    marker.addTo(map);
                    markers.push(marker);
                }
            });

            // Fit bounds to show all markers if data exists and no explicit filter is applied (optional UX enhancement)
            if (markers.length > 0 && mapData.length > 1) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));

                // Save the calculated bounds for reset button
                window.defaultBounds = group.getBounds().pad(0.1);
            }
        }

        window.resetView = function () {
            if (window.defaultBounds) {
                map.fitBounds(window.defaultBounds);
            } else {
                map.setView([defaultLat, defaultLng], defaultZoom);
            }
        }

        // --- ArcGIS Boundary Layers Implementation ---
        let arcgisLayers = {
            'kabupaten': null,
            'kecamatan': null,
            'desa': null
        };

        // URL file GeoJSON lokal di folder public/geojson
        const GEOJSON_URLS = {
            'kabupaten': '/geojson/kabupaten-bandung.geojson', // Menggunakan mock file sementara
            'kecamatan': '/geojson/batas_kecamatan.geojson',
            'desa': '/geojson/batas_desa.geojson'
        };

        const ARCGIS_STYLES = {
            'kabupaten': { color: '#9333ea', weight: 4, opacity: 0.8, fillOpacity: 0.05, dashArray: '10, 5' }, // Purple, tebal
            'kecamatan': { color: '#2563eb', weight: 2, opacity: 0.9, fillOpacity: 0.2, dashArray: '5, 5' },  // Akan ditimpa secara dinamis
            'desa': { color: '#16a34a', weight: 1, opacity: 0.7, fillOpacity: 0.4 }                      // Akan ditimpa secara dinamis
        };

        // Fungsi untuk menghasilkan warna unik yang konsisten berdasarkan string (nama)
        function getColorFromName(name) {
            if (!name) return '#9ca3af';
            let hash = 0;
            for (let i = 0; i < name.length; i++) {
                hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            // Palet warna yang cerah dan rapi
            const colors = [
                '#ef4444', '#f97316', '#f59e0b', '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4',
                '#0ea5e9', '#0284c7', '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e',
                '#b91c1c', '#c2410c', '#047857', '#0369a1', '#4338ca', '#be185d'
            ];
            const index = Math.abs(hash) % colors.length;
            return colors[index];
        }

        window.toggleArcGISLayer = function (layerType, isChecked) {
            if (isChecked) {
                // Jika layer belum dimuat
                if (!arcgisLayers[layerType]) {
                    
                    fetch(GEOJSON_URLS[layerType])
                        .then(response => {
                            if (!response.ok) throw new Error('Data GeoJSON tidak ditemukan');
                            return response.json();
                        })
                        .then(data => {
                            arcgisLayers[layerType] = L.geoJSON(data, {
                                style: function (feature) {
                                    let name = feature.properties.NAMOBJ || 
                                               feature.properties.NAMA || 
                                               feature.properties.WADMKC || 
                                               feature.properties.WADMKD || 
                                               feature.properties.NAMOBJ_1 ||
                                               feature.properties.Nama_Desa ||
                                               feature.properties.DESA ||
                                               'Wilayah';
                                    
                                    let baseStyle = Object.assign({}, ARCGIS_STYLES[layerType]);
                                    
                                    if (layerType === 'kecamatan' || layerType === 'desa') {
                                        let fillColor = getColorFromName(name);
                                        baseStyle.fillColor = fillColor;
                                        baseStyle.color = layerType === 'kecamatan' ? '#ffffff' : fillColor; // Border putih untuk kecamatan, sewarna untuk desa
                                    }
                                    
                                    return baseStyle;
                                },
                                onEachFeature: function (feature, layer) {
                                    // Berusaha mengekstrak nama wilayah (biasanya ada di properti NAMA, NAMOBJ, WADMKC, atau WADMKD)
                                    let name = feature.properties.NAMOBJ || 
                                               feature.properties.NAMA || 
                                               feature.properties.WADMKC || 
                                               feature.properties.WADMKD || 
                                               feature.properties.NAMOBJ_1 ||
                                               feature.properties.Nama_Desa ||
                                               feature.properties.DESA ||
                                               'Wilayah';
                                    layer.bindTooltip('<strong>' + name + '</strong>', { sticky: true, className: 'text-xs' });
                                }
                            }).addTo(map);
                            
                            // Optional: zoom to fit bounds when loaded
                            // map.fitBounds(arcgisLayers[layerType].getBounds());
                        })
                        .catch(err => {
                            console.error('Gagal memuat GeoJSON:', err);
                            alert('Gagal memuat batas wilayah. Pastikan file ' + GEOJSON_URLS[layerType] + ' ada di folder public.');
                            document.getElementById('layer-' + layerType).checked = false;
                        });
                } else {
                    // Jika sudah dimuat sebelumnya, cukup tambahkan kembali ke peta
                    arcgisLayers[layerType].addTo(map);
                }
            } else {
                // Sembunyikan layer
                if (arcgisLayers[layerType]) {
                    map.removeLayer(arcgisLayers[layerType]);
                }
            }
        }
    </script>
@endpush