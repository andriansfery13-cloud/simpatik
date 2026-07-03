@extends('layouts.app')

@section('title', 'Laporan Hasil Monev')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Cetak Berita Acara Monev
            </h1>
            <p class="text-sm text-gray-500 mt-1">Generate laporan monitoring dan evaluasi desa dalam format cetak (Berita Acara).</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <form action="{{ route('laporan.monev.cetak') }}" method="POST" target="_blank">
            @csrf
            
            <div class="p-6 space-y-8">
                
                {{-- Pemilihan Data --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">1. Pilih Data Anggaran & Tahap</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="form-label font-bold text-gray-700">Pilih Kecamatan</label>
                            <select id="kecamatan_select" class="form-select w-full" {{ $isKecamatanFixed ? 'disabled' : '' }}
                                onchange="filterByKecamatan(this.value)">
                                <option value="">-- Semua Kecamatan --</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}" {{ $selectedKecamatanId == $kec->id ? 'selected' : '' }}>
                                        {{ $kec->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="form-label font-bold text-gray-700">Pilih Desa</label>
                            <select id="desa_select" class="form-select w-full" onchange="filterByDesa(this.value)">
                                <option value="">-- Pilih Desa --</option>
                                @foreach($desas as $d)
                                    <option value="{{ $d->id }}" {{ $selectedDesa && $selectedDesa->id == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="form-label font-bold text-gray-700">Pilih Anggaran</label>
                            <select name="anggaran_id" class="form-select w-full" required {{ !$selectedDesa ? 'disabled' : '' }}>
                                <option value="">-- Pilih Anggaran --</option>
                                @foreach($anggarans as $a)
                                    <option value="{{ $a->id }}">
                                        {{ $a->sumberDana->kode }} - Tahun {{ $a->tahun_anggaran }} (Rp {{ number_format($a->pagu, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="form-label font-bold text-gray-700">Tahap (Opsional)</label>
                            <select name="tahap" class="form-select w-full" {{ !$selectedDesa ? 'disabled' : '' }}>
                                <option value="">-- Tidak Spesifik --</option>
                                <option value="TAHAP I">Tahap I</option>
                                <option value="TAHAP II">Tahap II</option>
                                <option value="TAHAP III">Tahap III</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Input Penandatangan --}}
                <div class="{{ !$selectedDesa ? 'opacity-50 pointer-events-none' : '' }}">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">2. Data Penandatangan (Manual)</h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="form-label font-bold text-gray-700">Kepala Desa</label>
                                <input type="text" name="kepala_desa" class="form-input" value="{{ $selectedDesa->kepala_desa ?? '' }}" required placeholder="Nama Kepala Desa">
                            </div>
                            <div class="space-y-2">
                                <label class="form-label font-bold text-gray-700">Ketua BPD</label>
                                <input type="text" name="ketua_bpd" class="form-input" required placeholder="Nama Ketua BPD">
                            </div>
                            <div class="space-y-2">
                                <label class="form-label font-bold text-gray-700">Ketua LPMD</label>
                                <input type="text" name="ketua_lpmd" class="form-input" required placeholder="Nama Ketua LPMD">
                            </div>
                            <div class="space-y-2">
                                <label class="form-label font-bold text-gray-700">Camat (Opsional)</label>
                                <input type="text" name="camat" class="form-input" placeholder="Kosongkan jika tidak perlu TTD Camat">
                            </div>
                        </div>

                        {{-- Tim Pembina Kecamatan --}}
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-800">Tim Pembina Kecamatan</h4>
                                <button type="button" onclick="addTimPembina()" class="btn-secondary text-xs px-2 py-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Anggota
                                </button>
                            </div>
                            
                            <div id="tim-pembina-container" class="space-y-3">
                                <div class="flex gap-3 items-center">
                                    <div class="flex-1">
                                        <input type="text" name="tim_pembina[0][nama]" class="form-input text-sm" required placeholder="Nama (Misal: Suryana, SE)">
                                    </div>
                                    <div class="w-1/3">
                                        <input type="text" name="tim_pembina[0][jabatan]" class="form-input text-sm" value="Ketua" required placeholder="Jabatan">
                                    </div>
                                    <div class="w-8"></div>
                                </div>
                                <div class="flex gap-3 items-center">
                                    <div class="flex-1">
                                        <input type="text" name="tim_pembina[1][nama]" class="form-input text-sm" required placeholder="Nama (Misal: Yani Kuswati, S.Pd)">
                                    </div>
                                    <div class="w-1/3">
                                        <input type="text" name="tim_pembina[1][jabatan]" class="form-input text-sm" value="Sekretaris" required placeholder="Jabatan">
                                    </div>
                                    <div class="w-8"></div>
                                </div>
                                <div class="flex gap-3 items-center" id="row-2">
                                    <div class="flex-1">
                                        <input type="text" name="tim_pembina[2][nama]" class="form-input text-sm" required placeholder="Nama Anggota">
                                    </div>
                                    <div class="w-1/3">
                                        <input type="text" name="tim_pembina[2][jabatan]" class="form-input text-sm" value="Anggota" required placeholder="Jabatan">
                                    </div>
                                    <button type="button" onclick="document.getElementById('row-2').remove()" class="text-red-500 hover:text-red-700 w-8 flex justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button type="submit" class="btn-primary {{ !$selectedDesa ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$selectedDesa ? 'disabled' : '' }}>
                    <svg class="w-5 h-5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Generate & Cetak Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let timIndex = 3;
    function addTimPembina() {
        const container = document.getElementById('tim-pembina-container');
        const row = document.createElement('div');
        row.className = 'flex gap-3 items-center mt-3';
        row.id = 'row-' + timIndex;
        
        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="tim_pembina[${timIndex}][nama]" class="form-input text-sm" required placeholder="Nama Anggota">
            </div>
            <div class="w-1/3">
                <input type="text" name="tim_pembina[${timIndex}][jabatan]" class="form-input text-sm" value="Anggota" required placeholder="Jabatan">
            </div>
            <button type="button" onclick="document.getElementById('row-${timIndex}').remove()" class="text-red-500 hover:text-red-700 w-8 flex justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
        
        container.appendChild(row);
        timIndex++;
    }

    function filterByKecamatan(kecamatanId) {
        let url = new URL(window.location.href);
        url.searchParams.delete('desa_id');
        if (kecamatanId) {
            url.searchParams.set('kecamatan_id', kecamatanId);
        } else {
            url.searchParams.delete('kecamatan_id');
        }
        window.location.href = url.toString();
    }

    function filterByDesa(desaId) {
        let url = new URL(window.location.href);
        if (desaId) {
            url.searchParams.set('desa_id', desaId);
        } else {
            url.searchParams.delete('desa_id');
        }
        window.location.href = url.toString();
    }
</script>
@endsection
