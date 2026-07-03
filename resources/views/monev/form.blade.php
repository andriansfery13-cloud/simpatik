@extends('layouts.app')

@section('title', 'Form Penilaian Monev')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in" x-data="{ tab: 'perencanaan' }">
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Form Penilaian Monev Desa</h1>
            <p class="text-sm text-gray-500 mt-1">Kegiatan: <strong>{{ $kegiatan->nama_kegiatan }}</strong> (Desa {{ $kegiatan->desa->nama }})</p>
        </div>
        <a href="{{ route('monev.wizard') }}" class="btn-secondary px-4 py-2">Kembali</a>
    </div>

    <form action="{{ route('monev.store', $kegiatan) }}" method="POST" class="space-y-6">
        @csrf

        {{-- Tab Navigation --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-2 flex overflow-x-auto gap-2">
            <button type="button" @click="tab = 'perencanaan'" :class="{ 'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'perencanaan', 'text-gray-500 hover:bg-gray-50': tab !== 'perencanaan' }" class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-colors">1. Perencanaan</button>
            <button type="button" @click="tab = 'keuangan'" :class="{ 'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'keuangan', 'text-gray-500 hover:bg-gray-50': tab !== 'keuangan' }" class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-colors">2. Keuangan</button>
            <button type="button" @click="tab = 'pelaksanaan'" :class="{ 'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'pelaksanaan', 'text-gray-500 hover:bg-gray-50': tab !== 'pelaksanaan' }" class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-colors">3. Pelaksanaan</button>
            <button type="button" @click="tab = 'fisik'" :class="{ 'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'fisik', 'text-gray-500 hover:bg-gray-50': tab !== 'fisik' }" class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-colors">4. Fisik Lapangan</button>
            <button type="button" @click="tab = 'pelaporan'" :class="{ 'bg-simpatik-50 text-simpatik-700 font-bold': tab === 'pelaporan', 'text-gray-500 hover:bg-gray-50': tab !== 'pelaporan' }" class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-colors">5. Pelaporan</button>
        </div>

        {{-- Tab Content: Perencanaan --}}
        <div x-show="tab === 'perencanaan'" class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">1. Administrasi Perencanaan</h3>
            <p class="text-sm text-gray-500 mb-6">Pilih dokumen yang tersedia dan valid di desa untuk kegiatan ini.</p>
            
            <div class="space-y-4">
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="perencanaan[]" value="rpjmdes" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">RPJMDes</p>
                        <p class="text-xs text-gray-500">Dokumen Rencana Pembangunan Jangka Menengah Desa tersedia dan memuat kegiatan ini.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="perencanaan[]" value="rkpdes" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">RKPDes</p>
                        <p class="text-xs text-gray-500">Kegiatan masuk dalam Rencana Kerja Pemerintah Desa tahun berjalan.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="perencanaan[]" value="apbdes" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">APBDes / Perdes APBDes</p>
                        <p class="text-xs text-gray-500">Anggaran telah ditetapkan dalam APBDes.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="perencanaan[]" value="musdes" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">Berita Acara Musdes</p>
                        <p class="text-xs text-gray-500">Terdapat bukti pelaksanaan Musyawarah Desa terkait perencanaan.</p>
                    </div>
                </label>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" @click="tab = 'keuangan'" class="btn-primary py-2 px-6">Selanjutnya</button>
            </div>
        </div>

        {{-- Tab Content: Keuangan --}}
        <div x-show="tab === 'keuangan'" style="display:none;" class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">2. Administrasi Keuangan</h3>
            
            <div class="space-y-4 mt-4">
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="keuangan[]" value="bku" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Buku Kas Umum (BKU)</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="keuangan[]" value="buku_bank" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Buku Bank & Rekening Koran</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="keuangan[]" value="buku_pajak" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Buku Pajak</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="keuangan[]" value="spj" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Register SPJ</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="keuangan[]" value="bukti_transfer" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Bukti Pengeluaran / Transfer Sah</p></div>
                </label>
            </div>
            <div class="mt-6 flex justify-between">
                <button type="button" @click="tab = 'perencanaan'" class="btn-secondary py-2 px-6">Sebelumnya</button>
                <button type="button" @click="tab = 'pelaksanaan'" class="btn-primary py-2 px-6">Selanjutnya</button>
            </div>
        </div>

        {{-- Tab Content: Pelaksanaan --}}
        <div x-show="tab === 'pelaksanaan'" style="display:none;" class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">3. Pelaksanaan Pengadaan & Kegiatan</h3>
            
            <div class="space-y-4 mt-4">
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="pelaksanaan[]" value="sk_tpk" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">SK TPK (Tim Pelaksana Kegiatan)</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="pelaksanaan[]" value="rab" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">RAB & Gambar Teknis</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="pelaksanaan[]" value="kontrak" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Kontrak Kerja / Surat Pesanan</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="pelaksanaan[]" value="dokumentasi" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Dokumentasi Pelaksanaan (0%, 50%, 100%)</p></div>
                </label>
            </div>
            <div class="mt-6 flex justify-between">
                <button type="button" @click="tab = 'keuangan'" class="btn-secondary py-2 px-6">Sebelumnya</button>
                <button type="button" @click="tab = 'fisik'" class="btn-primary py-2 px-6">Selanjutnya</button>
            </div>
        </div>

        {{-- Tab Content: Fisik --}}
        <div x-show="tab === 'fisik'" style="display:none;" class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">4. Monitoring Fisik Lapangan</h3>
            
            <div class="space-y-4 mt-4">
                <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg mb-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="fisik[]" value="bukan_fisik" class="mt-1 form-checkbox text-blue-600 rounded">
                        <div>
                            <p class="font-bold text-blue-800">Bukan Kegiatan Fisik</p>
                            <p class="text-xs text-blue-600">Centang opsi ini jika kegiatan ini berupa pengadaan barang/jasa non-fisik atau pelatihan (otomatis skor fisik 100).</p>
                        </div>
                    </label>
                </div>

                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="fisik[]" value="lokasi" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">Kesesuaian Lokasi</p>
                        <p class="text-xs text-gray-500">Pembangunan berada tepat di lokasi yang direncanakan.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="fisik[]" value="volume" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">Kesesuaian Volume</p>
                        <p class="text-xs text-gray-500">Panjang, lebar, dan tinggi bangunan sesuai dengan RAB.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="fisik[]" value="kualitas" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">Kualitas Material & Pengerjaan</p>
                        <p class="text-xs text-gray-500">Spesifikasi material yang digunakan sesuai standar mutu di RAB.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="fisik[]" value="papan_proyek" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div>
                        <p class="font-bold text-gray-700">Pemasangan Papan Informasi Proyek</p>
                        <p class="text-xs text-gray-500">Papan terpasang di lokasi sebagai bentuk transparansi.</p>
                    </div>
                </label>
            </div>
            <div class="mt-6 flex justify-between">
                <button type="button" @click="tab = 'pelaksanaan'" class="btn-secondary py-2 px-6">Sebelumnya</button>
                <button type="button" @click="tab = 'pelaporan'" class="btn-primary py-2 px-6">Selanjutnya</button>
            </div>
        </div>

        {{-- Tab Content: Pelaporan --}}
        <div x-show="tab === 'pelaporan'" style="display:none;" class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">5. Pelaporan & Pertanggungjawaban</h3>
            
            <div class="space-y-4 mt-4">
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="pelaporan[]" value="lppd" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Laporan Penyelenggaraan Pemerintahan Desa (LPPD)</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="pelaporan[]" value="lKPJ" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Laporan Keterangan Pertanggungjawaban (LKPJ)</p></div>
                </label>
                <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="pelaporan[]" value="serah_terima" class="mt-1 form-checkbox text-simpatik-600 rounded">
                    <div><p class="font-bold text-gray-700">Berita Acara Serah Terima Hasil Pekerjaan</p></div>
                </label>
            </div>

            <div class="mt-8 p-4 bg-simpatik-50 border border-simpatik-200 rounded-lg">
                <p class="text-sm text-simpatik-800 font-medium text-center">Pastikan semua data yang diisi telah sesuai dengan kondisi fisik dan administrasi di lapangan.</p>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" @click="tab = 'fisik'" class="btn-secondary py-2 px-6">Sebelumnya</button>
                <button type="submit" class="btn-primary py-2 px-8 bg-green-600 hover:bg-green-700 border-none text-white font-bold" onclick="return confirm('Apakah Anda yakin ingin menyimpan hasil penilaian Monev ini?')">Simpan Hasil Monev</button>
            </div>
        </div>

    </form>
</div>
@endsection
