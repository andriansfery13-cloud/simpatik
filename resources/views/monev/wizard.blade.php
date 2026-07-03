@extends('layouts.app')

@section('title', 'Wizard Monev Desa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pilih Sasaran Monev</h1>
            <p class="text-sm text-gray-500 mt-1">Ikuti langkah-langkah berikut untuk memulai Monitoring & Evaluasi kegiatan desa.</p>
        </div>
        <a href="{{ route('monev.index') }}" class="btn-secondary px-4 py-2 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
        <form action="{{ route('monev.wizard') }}" method="GET" class="space-y-6">
            
            {{-- Step 1: Pilih Desa --}}
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-simpatik-100 text-simpatik-600 font-bold flex items-center justify-center shrink-0">1</div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Desa</label>
                    <select name="desa_id" onchange="this.form.submit()" class="form-select w-full" required>
                        <option value="">-- Pilih Desa --</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ ($selectedDesa->id ?? '') == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama }} (Kec. {{ $desa->kecamatan->nama }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Step 2: Pilih Anggaran (Muncul jika desa sudah dipilih) --}}
            @if($selectedDesa)
            <div class="flex items-start gap-4 animate-slide-up">
                <div class="w-8 h-8 rounded-full bg-simpatik-100 text-simpatik-600 font-bold flex items-center justify-center shrink-0">2</div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Sumber Anggaran / TA</label>
                    <select name="anggaran_id" onchange="this.form.submit()" class="form-select w-full" required>
                        <option value="">-- Pilih Anggaran --</option>
                        @foreach($anggarans as $anggaran)
                            <option value="{{ $anggaran->id }}" {{ ($selectedAnggaran->id ?? '') == $anggaran->id ? 'selected' : '' }}>
                                {{ $anggaran->sumberDana->nama }} - TA {{ $anggaran->tahun_anggaran }} (Pagu: Rp {{ number_format($anggaran->pagu, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            {{-- Step 3: Pilih Kegiatan (Muncul jika anggaran sudah dipilih) --}}
            @if($selectedAnggaran)
            <div class="flex items-start gap-4 animate-slide-up">
                <div class="w-8 h-8 rounded-full bg-simpatik-100 text-simpatik-600 font-bold flex items-center justify-center shrink-0">3</div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Kegiatan untuk di-Monev</label>
                    
                    @if($kegiatans->count() > 0)
                        <div class="space-y-3">
                            @foreach($kegiatans as $kegiatan)
                                <div class="p-4 border border-gray-200 rounded-lg hover:border-simpatik-300 hover:shadow-md transition-all flex items-center justify-between {{ $kegiatan->monev ? 'bg-green-50/30' : '' }}">
                                    <div>
                                        <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                            {{ $kegiatan->nama_kegiatan }}
                                            @if($kegiatan->monev)
                                                <span class="px-2 py-0.5 rounded text-[10px] bg-green-100 text-green-700 font-bold border border-green-200">✅ Sudah di-Monev</span>
                                            @endif
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">Status: <span class="badge badge-{{ $kegiatan->status_color }}">{{ $kegiatan->status_label }}</span> | Pagu: Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</p>
                                    </div>
                                    @if($kegiatan->monev)
                                        <a href="{{ route('monev.show', $kegiatan->monev) }}" class="btn-secondary py-2 px-4 whitespace-nowrap text-sm border-green-600 text-green-700 hover:bg-green-50 hover:text-green-800">
                                            Lihat Hasil Monev
                                        </a>
                                    @else
                                        <a href="{{ route('monev.create', $kegiatan) }}" class="btn-primary py-2 px-4 whitespace-nowrap text-sm">
                                            Mulai Penilaian
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 bg-yellow-50 text-yellow-800 border border-yellow-200 rounded-lg text-sm">
                            Tidak ada kegiatan yang terdaftar pada sumber dana dan tahun anggaran ini.
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
