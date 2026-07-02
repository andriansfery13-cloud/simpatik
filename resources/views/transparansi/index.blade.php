<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPATIK - Portal Transparansi Publik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-simpatik-800 rounded-xl flex items-center justify-center shadow-lg text-white">
                        <span class="text-xl">🌿</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight text-gray-900 leading-tight">Portal Transparansi</h1>
                        <p class="text-[10px] text-gray-500 font-medium leading-tight">SIMPATIK - Kabupaten Bandung BEDAS</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-simpatik-600 hover:text-simpatik-800">
                        Login Aparatur &rarr;
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Banner --}}
    <div class="bg-gradient-to-br from-simpatik-900 to-simpatik-700 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1596422846543-75c6fc197f07?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight mb-4 text-transparent bg-clip-text bg-gradient-to-r from-white to-simpatik-200">
                Kawal Pembangunan Desa Bersama
            </h1>
            <p class="text-lg text-simpatik-100 max-w-2xl mx-auto">
                Akses informasi secara terbuka mengenai alokasi anggaran dan progres pembangunan desa di seluruh wilayah Kabupaten Bandung.
            </p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-6 grid grid-cols-1 md:grid-cols-4 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            <div class="text-center px-4">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Kegiatan</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_kegiatan']) }}</p>
            </div>
            <div class="text-center px-4">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Anggaran</p>
                <p class="text-2xl font-bold text-simpatik-600">Rp {{ number_format($stats['total_anggaran'] / 1000000000, 2, ',', '.') }} M</p>
            </div>
            <div class="text-center px-4">
                <p class="text-sm text-gray-500 font-medium mb-1">Rata-rata Progres</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['rata_progres'] }}%</p>
            </div>
            <div class="text-center px-4">
                <p class="text-sm text-gray-500 font-medium mb-1">Kegiatan Selesai</p>
                <p class="text-3xl font-bold text-green-600">{{ number_format($stats['total_selesai']) }}</p>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Filters --}}
        <div x-data="filterForm()" class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-8">
            <form action="{{ route('transparansi.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Pencarian</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kegiatan..." class="form-input border-gray-300 w-full">
                </div>
                
                <div class="w-full md:w-32">
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Tahun</label>
                    <select name="tahun_anggaran" class="form-select border-gray-300 w-full">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $selectedTahun == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-40">
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Semester</label>
                    <select name="semester" class="form-select border-gray-300 w-full">
                        <option value="">Semua Semester</option>
                        <option value="1" {{ $selectedSemester == '1' ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ $selectedSemester == '2' ? 'selected' : '' }}>Semester 2</option>
                        <option value="3" {{ $selectedSemester == '3' ? 'selected' : '' }}>Semester 3</option>
                    </select>
                </div>

                <div class="w-full md:w-48">
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Kecamatan</label>
                    <select name="kecamatan_id" x-model="kecamatanId" @change="loadDesas()" class="form-select border-gray-300 w-full">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-48">
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Desa</label>
                    <select name="desa_id" x-model="desaId" class="form-select border-gray-300 w-full" :disabled="!kecamatanId">
                        <option value="">Semua Desa</option>
                        <template x-for="desa in desas" :key="desa.id">
                            <option :value="desa.id" x-text="desa.nama"></option>
                        </template>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-primary py-2 px-6 h-[42px]">Terapkan Filter</button>
                </div>
            </form>
        </div>

        {{-- Data Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kegiatans as $kegiatan)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden flex flex-col">
                    <div class="p-5 flex-1">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">
                                {{ $kegiatan->sumberDana->kode }}
                            </span>
                            <span class="badge badge-{{ $kegiatan->status_color }}">
                                {{ $kegiatan->status_label }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1 line-clamp-2" title="{{ $kegiatan->nama_kegiatan }}">{{ $kegiatan->nama_kegiatan }}</h3>
                        <p class="text-xs text-gray-500 mb-4 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            Desa {{ $kegiatan->desa->nama }}, Kec. {{ $kegiatan->desa->kecamatan->nama }}
                        </p>
                        
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 mb-4">
                            <p class="text-[10px] text-gray-500 mb-1">Anggaran Disediakan</p>
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-gray-600">Progres Pekerjaan</span>
                                <span class="font-bold {{ $kegiatan->progres_fisik < 50 ? 'text-yellow-600' : 'text-simpatik-600' }}">{{ $kegiatan->progres_fisik }}%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill {{ $kegiatan->progres_fisik < 50 ? 'bg-yellow-400' : 'bg-simpatik-500' }}" style="width: {{ $kegiatan->progres_fisik }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 p-3 bg-gray-50">
                        <button class="w-full text-center text-xs font-semibold text-simpatik-600 hover:text-simpatik-800">
                            Lihat Detail Proyek & Dokumentasi &rarr;
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 text-center rounded-xl border border-gray-200">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada data ditemukan</h3>
                    <p class="text-gray-500">Coba ubah kata kunci pencarian atau filter wilayah.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $kegiatans->links() }}
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-8 border-t-4 border-simpatik-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/20">
                <span class="text-2xl">🏛</span>
            </div>
            <h3 class="text-lg font-bold mb-1">Pemerintah Kabupaten Bandung</h3>
            <p class="text-sm text-gray-400 mb-6">Program Inovasi SIMPATIK (Sistem Monitoring Pembangunan Terintegrasi Kecamatan)</p>
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </footer>
    
    <script>
    function filterForm() {
        return {
            kecamatanId: '{{ $selectedKecId ?? "" }}',
            desaId: '{{ $selectedDesaId ?? "" }}',
            desas: @json($desas ?? []),
            
            async loadDesas() {
                if (!this.kecamatanId) {
                    this.desas = [];
                    this.desaId = '';
                    return;
                }
                try {
                    const response = await fetch(`/api/desa-by-kecamatan/${this.kecamatanId}`);
                    if (response.ok) {
                        this.desas = await response.json();
                        // Reset desa if current desa doesn't belong to the newly selected kecamatan
                        if (!this.desas.some(d => d.id == this.desaId)) {
                            this.desaId = '';
                        }
                    }
                } catch (e) {
                    this.desas = [];
                }
            }
        }
    }
    </script>
</body>
</html>
