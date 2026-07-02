@extends('layouts.app')

@section('title', 'Generator Laporan')

@section('content')
<div class="space-y-6 animate-fade-in max-w-5xl mx-auto" x-data="laporanForm()">
    
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-7 h-7 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Generator Laporan
            </h1>
            <p class="text-sm text-gray-500 mt-1">Buat laporan komprehensif pembangunan berdasarkan data riil sistem.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-simpatik-50 border border-simpatik-200 text-simpatik-700 text-xs font-semibold rounded-full">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                Data Real-Time
            </span>
        </div>
    </div>

    {{-- Form Card --}}
    <form method="POST" action="{{ route('laporan.generate') }}" target="_blank" class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        @csrf

        {{-- Section 1: Jenis Laporan --}}
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-5">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">1</span>
                Jenis Laporan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label class="relative cursor-pointer group">
                    <input type="radio" name="jenis_laporan" value="rekapitulasi_anggaran" x-model="jenis" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-simpatik-500 peer-checked:bg-simpatik-50/50 hover:border-gray-300 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💰</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800">Rekapitulasi Realisasi Anggaran</p>
                                <p class="text-xs text-gray-500 mt-0.5">Laporan pagu vs realisasi anggaran per kegiatan desa</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer group">
                    <input type="radio" name="jenis_laporan" value="progres_fisik" x-model="jenis" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-simpatik-500 peer-checked:bg-simpatik-50/50 hover:border-gray-300 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🏗️</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800">Progres Fisik Konstruksi</p>
                                <p class="text-xs text-gray-500 mt-0.5">Detail capaian progres fisik tiap kegiatan pembangunan</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer group">
                    <input type="radio" name="jenis_laporan" value="evaluasi_kinerja" x-model="jenis" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-simpatik-500 peer-checked:bg-simpatik-50/50 hover:border-gray-300 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📊</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800">Evaluasi & Kinerja (MONEV)</p>
                                <p class="text-xs text-gray-500 mt-0.5">Analisis kinerja pelaksanaan program per wilayah</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer group">
                    <input type="radio" name="jenis_laporan" value="laporan_eksekutif" x-model="jenis" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-simpatik-500 peer-checked:bg-simpatik-50/50 hover:border-gray-300 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📋</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800">Laporan Eksekutif</p>
                                <p class="text-xs text-gray-500 mt-0.5">Ringkasan eksekutif seluruh program untuk pimpinan</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Section 2: Filter --}}
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-5">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">2</span>
                Parameter Filter
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Tahun --}}
                <div>
                    <label for="tahun_anggaran" class="form-label">Tahun Anggaran <span class="text-red-500">*</span></label>
                    <select name="tahun_anggaran" id="tahun_anggaran" class="form-select w-full">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn }}" {{ $thn == now()->year ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kecamatan --}}
                <div>
                    <label for="kecamatan_id" class="form-label">Kecamatan</label>
                    <select name="kecamatan_id" id="kecamatan_id" x-model="kecamatanId" @change="loadDesas()" class="form-select w-full" {{ $isKecamatanFixed ? 'disabled' : '' }}>
                        @if(!$isKecamatanFixed)
                            <option value="">Semua Kecamatan</option>
                        @endif
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                    @if($isKecamatanFixed)
                        <input type="hidden" name="kecamatan_id" value="{{ $selectedKecamatanId }}">
                    @endif
                </div>

                {{-- Desa --}}
                <div>
                    <label for="desa_id" class="form-label">Desa</label>
                    <select name="desa_id" id="desa_id" x-model="desaId" class="form-select w-full" :disabled="isDesaFixed || !kecamatanId" {{ $isDesaFixed ? 'disabled' : '' }}>
                        @if(!$isDesaFixed)
                            <option value="">Semua Desa</option>
                        @endif
                        <template x-for="desa in desas" :key="desa.id">
                            <option :value="desa.id" x-text="desa.nama"></option>
                        </template>
                    </select>
                    @if($isDesaFixed)
                        <input type="hidden" name="desa_id" value="{{ $selectedDesaId }}">
                    @endif
                </div>
            </div>
        </div>

        {{-- Section 3: Opsi --}}
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-5">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">3</span>
                Opsi Tambahan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="include_foto" value="1" checked class="text-simpatik-600 rounded border-gray-300 focus:ring-simpatik-500 h-4 w-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700">📷 Sertakan Foto</span>
                        <span class="text-xs text-gray-500 block">Lampiran foto dokumentasi</span>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="include_koordinat" value="1" checked class="text-simpatik-600 rounded border-gray-300 focus:ring-simpatik-500 h-4 w-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700">📍 Titik Koordinat</span>
                        <span class="text-xs text-gray-500 block">Lokasi GPS kegiatan</span>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="include_summary" value="1" checked class="text-simpatik-600 rounded border-gray-300 focus:ring-simpatik-500 h-4 w-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700">📊 Ringkasan Statistik</span>
                        <span class="text-xs text-gray-500 block">Tabel rekapitulasi data</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-xs text-gray-500 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-simpatik-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Laporan akan dibuka di tab baru dan bisa langsung dicetak / simpan sebagai PDF.
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary py-2.5 px-6 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Generate Laporan
                </button>
            </div>
        </div>
    </form>

    {{-- Quick Stats Preview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-card border border-gray-100 text-center">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <p class="text-[10px] text-gray-500 uppercase font-semibold">4 Jenis Laporan</p>
            <p class="text-xs text-gray-600 mt-1">Tersedia</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-card border border-gray-100 text-center">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </div>
            <p class="text-[10px] text-gray-500 uppercase font-semibold">Cetak / PDF</p>
            <p class="text-xs text-gray-600 mt-1">Print-Ready</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-card border border-gray-100 text-center">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </div>
            <p class="text-[10px] text-gray-500 uppercase font-semibold">Filter Dinamis</p>
            <p class="text-xs text-gray-600 mt-1">Kecamatan & Desa</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-card border border-gray-100 text-center">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-[10px] text-gray-500 uppercase font-semibold">Real-time</p>
            <p class="text-xs text-gray-600 mt-1">Data Terkini</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function laporanForm() {
    return {
        jenis: 'rekapitulasi_anggaran',
        kecamatanId: '{{ $selectedKecamatanId ?? "" }}',
        desaId: '{{ $selectedDesaId ?? "" }}',
        desas: @json($desas ?? []),
        isKecamatanFixed: {{ $isKecamatanFixed ? 'true' : 'false' }},
        isDesaFixed: {{ $isDesaFixed ? 'true' : 'false' }},
        
        async loadDesas() {
            if (this.isKecamatanFixed && this.desas.length > 0) return; // already preloaded
            if (!this.kecamatanId) {
                this.desas = [];
                return;
            }
            try {
                const response = await fetch(`/api/desa-by-kecamatan/${this.kecamatanId}`);
                if (response.ok) {
                    this.desas = await response.json();
                }
            } catch (e) {
                this.desas = [];
            }
        }
    };
}
</script>
@endpush
