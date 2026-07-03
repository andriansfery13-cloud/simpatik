@extends('layouts.app')

@section('title', 'Detail Hasil Monev')

@section('content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Hasil Monev Desa</h1>
            <p class="text-sm text-gray-500 mt-1">
                Kegiatan: <span class="font-bold text-gray-700">{{ $monev->kegiatan->nama_kegiatan }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('monev.index') }}" class="btn-secondary px-4 py-2">Kembali ke Dashboard</a>
            @if(!$monev->ai_insight)
                <form action="{{ route('monev.generate_ai', $monev) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary py-2 px-4 flex items-center gap-2 bg-purple-600 hover:bg-purple-700 border-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Generate AI Insight
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: AI Insight & Summary --}}
        <div class="space-y-6 lg:col-span-1">
            {{-- Total Score Card --}}
            <div class="bg-white rounded-xl p-6 text-gray-800 shadow-card border border-gray-100 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-green-500"></div>
                <p class="text-gray-500 text-sm font-medium mb-2 uppercase tracking-wide">Nilai Akhir Monev</p>
                <h2 class="text-5xl font-black mb-3 text-gray-900">{{ number_format($monev->skor_total, 1) }}</h2>
                <div class="inline-block px-4 py-1.5 rounded-full text-sm font-bold bg-green-50 text-green-700 border border-green-200">
                    Kategori: {{ $monev->kategori }}
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-100 text-left text-sm space-y-3">
                    <p class="flex justify-between items-center"><span class="text-gray-500">Desa</span> <span class="font-bold text-gray-800 text-right">{{ $monev->desa->nama }}</span></p>
                    <p class="flex justify-between items-center"><span class="text-gray-500">Tanggal Penilaian</span> <span class="font-bold text-gray-800 text-right">{{ $monev->tanggal_monev->format('d M Y') }}</span></p>
                    <p class="flex justify-between items-center"><span class="text-gray-500">Penilai</span> <span class="font-bold text-gray-800 text-right">{{ $monev->user->name }}</span></p>
                </div>
            </div>

            {{-- AI Insight Card --}}
            <div class="bg-white rounded-xl shadow-card border border-purple-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 to-blue-500"></div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">AI Monev Insight</h3>
                    </div>
                    
                    @if($monev->ai_insight)
                        <div class="prose prose-sm text-gray-600 prose-ul:my-1 prose-li:my-0.5 max-w-none">
                            {!! \Illuminate\Support\Str::markdown($monev->ai_insight) !!}
                        </div>
                        <p class="text-[10px] text-gray-400 mt-4 italic text-right">Di-generate otomatis oleh OpenAI Model.</p>
                    @else
                        <div class="text-center py-6">
                            <div class="w-24 h-24 mx-auto bg-purple-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Klik tombol "Generate AI Insight" di atas untuk mendapatkan analisa cerdas dari asisten AI terkait hasil monev ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Detailed Breakdown --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Perencanaan --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4 border-b pb-2 border-gray-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">1</span>
                        Administrasi Perencanaan
                    </h3>
                    <span class="font-bold text-lg {{ $monev->skor_perencanaan >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $monev->skor_perencanaan }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach(['rpjmdes' => 'RPJMDes', 'rkpdes' => 'RKPDes', 'apbdes' => 'APBDes', 'musdes' => 'Berita Acara Musdes'] as $key => $label)
                        <div class="flex items-center gap-2">
                            @if(in_array($key, $monev->aspek_perencanaan ?? []))
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700">{{ $label }}</span>
                            @else
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="text-gray-400 line-through">{{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Keuangan --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4 border-b pb-2 border-gray-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs">2</span>
                        Administrasi Keuangan
                    </h3>
                    <span class="font-bold text-lg {{ $monev->skor_keuangan >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $monev->skor_keuangan }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach(['bku' => 'Buku Kas Umum', 'buku_bank' => 'Buku Bank', 'buku_pajak' => 'Buku Pajak', 'spj' => 'Register SPJ', 'bukti_transfer' => 'Bukti Pengeluaran'] as $key => $label)
                        <div class="flex items-center gap-2">
                            @if(in_array($key, $monev->aspek_keuangan ?? []))
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700">{{ $label }}</span>
                            @else
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="text-gray-400 line-through">{{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pelaksanaan --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4 border-b pb-2 border-gray-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs">3</span>
                        Pelaksanaan Kegiatan
                    </h3>
                    <span class="font-bold text-lg {{ $monev->skor_pelaksanaan >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $monev->skor_pelaksanaan }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach(['sk_tpk' => 'SK TPK', 'rab' => 'RAB & Gambar Teknis', 'kontrak' => 'Kontrak Kerja', 'dokumentasi' => 'Dokumentasi Pelaksanaan'] as $key => $label)
                        <div class="flex items-center gap-2">
                            @if(in_array($key, $monev->aspek_pelaksanaan ?? []))
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700">{{ $label }}</span>
                            @else
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="text-gray-400 line-through">{{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Fisik --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4 border-b pb-2 border-gray-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">4</span>
                        Monitoring Fisik Lapangan
                    </h3>
                    <span class="font-bold text-lg {{ $monev->skor_fisik >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $monev->skor_fisik }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach(['lokasi' => 'Kesesuaian Lokasi', 'volume' => 'Kesesuaian Volume', 'kualitas' => 'Kualitas Material & Pengerjaan', 'papan_proyek' => 'Pemasangan Papan Proyek'] as $key => $label)
                        <div class="flex items-center gap-2">
                            @if(in_array($key, $monev->aspek_fisik ?? []))
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700">{{ $label }}</span>
                            @else
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="text-gray-400 line-through">{{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pelaporan --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4 border-b pb-2 border-gray-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs">5</span>
                        Pelaporan & Pertanggungjawaban
                    </h3>
                    <span class="font-bold text-lg {{ $monev->skor_pelaporan >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $monev->skor_pelaporan }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach(['lppd' => 'LPPD', 'lKPJ' => 'LKPJ', 'serah_terima' => 'Berita Acara Serah Terima'] as $key => $label)
                        <div class="flex items-center gap-2">
                            @if(in_array($key, $monev->aspek_pelaporan ?? []))
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-700">{{ $label }}</span>
                            @else
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="text-gray-400 line-through">{{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
