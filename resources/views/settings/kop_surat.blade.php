@extends('layouts.app')

@section('title', 'Pengaturan Kop Surat')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path></svg>
            Pengaturan Kop Surat
        </h1>
        <p class="text-sm text-gray-500 mt-1">Atur kop surat resmi yang akan digunakan saat mencetak Berita Acara dan Laporan.</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <form action="{{ route('settings.kop-surat.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                {{-- Preview --}}
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-center relative overflow-hidden">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Preview Kop Surat</p>
                    <div class="flex items-center justify-center gap-6 py-2">
                        @if($kopSurat && $kopSurat->logo_path)
                            <img src="{{ asset('storage/' . $kopSurat->logo_path) }}" alt="Logo" class="h-20 w-auto object-contain">
                        @else
                            <div class="h-20 w-20 bg-gray-200 flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300">
                                <span class="text-xs text-gray-400">Logo</span>
                            </div>
                        @endif
                        
                        <div class="text-center flex-1 max-w-lg">
                            <h2 class="text-xl font-bold text-gray-900 leading-tight uppercase">{{ $kopSurat->pemerintah ?? 'PEMERINTAH DAERAH' }}</h2>
                            <h3 class="text-2xl font-black text-gray-900 leading-tight tracking-wide uppercase">{{ $kopSurat->instansi ?? 'NAMA INSTANSI' }}</h3>
                            <p class="text-sm text-gray-700 mt-1">{{ $kopSurat->alamat ?? 'Alamat Lengkap Instansi' }}</p>
                            <p class="text-sm text-gray-700">{{ $kopSurat->kontak ?? 'Telepon / Fax / Email' }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="form-label font-bold text-gray-700">Nama Pemerintah Daerah</label>
                        <input type="text" name="pemerintah" class="form-input" value="{{ old('pemerintah', $kopSurat->pemerintah ?? '') }}" placeholder="Contoh: PEMERINTAH KABUPATEN BANDUNG">
                        <p class="text-xs text-gray-500">Teks baris pertama (huruf besar).</p>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label font-bold text-gray-700">Nama Instansi</label>
                        <input type="text" name="instansi" class="form-input" value="{{ old('instansi', $kopSurat->instansi ?? '') }}" placeholder="Contoh: KECAMATAN CIPARAY / DESA CIPARAY">
                        <p class="text-xs text-gray-500">Teks baris kedua, tercetak tebal dan besar.</p>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="form-label font-bold text-gray-700">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-input" rows="2" placeholder="Contoh: Jalan Pamageran No. 2 Ciparay 40381...">{{ old('alamat', $kopSurat->alamat ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="form-label font-bold text-gray-700">Kontak (Telp/Fax/Email/Web)</label>
                        <input type="text" name="kontak" class="form-input" value="{{ old('kontak', $kopSurat->kontak ?? '') }}" placeholder="Contoh: Telp. (022) 5950372 Email: kec.ciparay@bandungkab.go.id">
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="form-label font-bold text-gray-700">Logo Instansi</label>
                        <input type="file" name="logo" class="form-input" accept="image/*">
                        <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah logo saat ini. Format: JPG/PNG, Maksimal: 2MB.</p>
                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <a href="{{ url()->previous() }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Kop Surat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
