<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $jenisLabel }} - SIMPATIK</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN for standalone print view) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        simpatik: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            body {
                background-color: white !important;
                font-size: 11pt;
            }
            .no-print {
                display: none !important;
            }
            .print-break-inside-avoid {
                break-inside: avoid;
            }
            .print-page-break {
                page-break-before: always;
            }
            .shadow-sm, .shadow, .shadow-md, .shadow-lg {
                box-shadow: none !important;
            }
            .border-gray-200 {
                border-color: #e5e7eb !important;
            }
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen py-8">

    {{-- Toolbar (No Print) --}}
    <div class="max-w-6xl mx-auto mb-6 no-print flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <a href="{{ route('laporan.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <div class="h-6 w-px bg-gray-300"></div>
            <span class="text-sm text-gray-600 font-medium">Preview Laporan</span>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="bg-simpatik-600 hover:bg-simpatik-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Report Document --}}
    <div class="max-w-7xl mx-auto bg-white p-10 md:p-12 shadow-lg md:rounded-lg border border-gray-200 min-h-screen">
        
        {{-- Report Header --}}
        <div class="border-b-4 border-gray-800 pb-6 mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gray-100 border-2 border-gray-800 rounded-full flex items-center justify-center">
                    <span class="text-3xl">🏛</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold uppercase tracking-tight text-gray-900">Pemerintah Kabupaten Bandung</h1>
                    <h2 class="text-lg font-semibold text-gray-700">Sistem Monitoring Pembangunan Terintegrasi (SIMPATIK)</h2>
                    <p class="text-sm text-gray-500 mt-1">Soreang, Kabupaten Bandung, Jawa Barat</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-900 uppercase">Dokumen Laporan</p>
                <p class="text-sm text-gray-600 mt-1">{{ now()->isoFormat('D MMMM Y') }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Dicetak oleh: {{ auth()->user()->name }}</p>
            </div>
        </div>

        {{-- Report Title Section --}}
        <div class="text-center mb-10">
            <h3 class="text-xl font-bold text-gray-900 uppercase underline mb-2">{{ $jenisLabel }}</h3>
            <p class="text-md font-semibold text-gray-700 uppercase">Tahun Anggaran {{ $tahun }}</p>
            <p class="text-sm text-gray-600 mt-1">Wilayah: <span class="font-semibold">{{ $wilayahLabel }}</span></p>
        </div>

        {{-- Executive Summary (If checked) --}}
        <div class="mb-10 p-6 bg-gray-50 border border-gray-200 rounded-lg print-break-inside-avoid">
            <h4 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Ringkasan Statistik Eksekutif</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Kegiatan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_kegiatan'], 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Pagu Anggaran</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($summary['total_pagu'], 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Realisasi</p>
                    <p class="text-xl font-bold text-simpatik-700">Rp {{ number_format($summary['total_realisasi'], 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Daya Serap / Fisik</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['persentase_keuangan'] }}% / {{ $summary['rata_progres_fisik'] }}%</p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-xl font-bold text-green-600">{{ $summary['kegiatan_selesai'] }}</p>
                    <p class="text-xs text-gray-500 font-medium">Selesai</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-blue-600">{{ $summary['kegiatan_berjalan'] }}</p>
                    <p class="text-xs text-gray-500 font-medium">Berjalan</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-red-600">{{ $summary['kegiatan_terlambat'] }}</p>
                    <p class="text-xs text-gray-500 font-medium">Terlambat</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-600">{{ $summary['kegiatan_belum_mulai'] }}</p>
                    <p class="text-xs text-gray-500 font-medium">Belum Mulai</p>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="mb-10 print-break-inside-avoid">
            <h4 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Rincian Data Kegiatan</h4>
            
            @if($kegiatans->isEmpty())
                <div class="text-center py-10 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-gray-500">Tidak ada data kegiatan untuk wilayah dan tahun ini.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 text-xs text-gray-700 uppercase font-semibold">
                            <th class="border border-gray-300 px-3 py-2 text-center w-10">No</th>
                            <th class="border border-gray-300 px-3 py-2 w-48">Nama Kegiatan</th>
                            <th class="border border-gray-300 px-3 py-2 w-32">Lokasi (Desa/Kec)</th>
                            <th class="border border-gray-300 px-3 py-2 w-32">Sumber Dana</th>
                            <th class="border border-gray-300 px-3 py-2 text-right">Pagu (Rp)</th>
                            <th class="border border-gray-300 px-3 py-2 text-right">Realisasi (Rp)</th>
                            <th class="border border-gray-300 px-3 py-2 text-center w-24">Keuangan</th>
                            <th class="border border-gray-300 px-3 py-2 text-center w-24">Fisik</th>
                            <th class="border border-gray-300 px-3 py-2 text-center w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-gray-700">
                        @foreach($kegiatans as $index => $kegiatan)
                            <tr>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-3 py-2 font-medium text-gray-900">{{ $kegiatan->nama_kegiatan }}</td>
                                <td class="border border-gray-300 px-3 py-2">
                                    {{ $kegiatan->desa->nama ?? '-' }}<br>
                                    <span class="text-[10px] text-gray-500">{{ $kegiatan->desa->kecamatan->nama ?? '-' }}</span>
                                </td>
                                <td class="border border-gray-300 px-3 py-2">{{ $kegiatan->sumberDana->nama ?? '-' }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-right">{{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-right">{{ number_format($kegiatan->realisasi_anggaran, 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ $kegiatan->persentase_keuangan }}%</td>
                                <td class="border border-gray-300 px-3 py-2 text-center font-bold {{ $kegiatan->progres_fisik == 100 ? 'text-green-600' : '' }}">{{ $kegiatan->progres_fisik }}%</td>
                                <td class="border border-gray-300 px-3 py-2 text-center uppercase text-[10px] font-bold">
                                    @if($kegiatan->status == 'selesai')
                                        <span class="text-green-600">Selesai</span>
                                    @elseif($kegiatan->status == 'berjalan')
                                        <span class="text-blue-600">Berjalan</span>
                                    @elseif($kegiatan->status == 'terlambat')
                                        <span class="text-red-600">Terlambat</span>
                                    @else
                                        <span class="text-gray-500">Belum Mulai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold text-xs text-gray-900">
                        <tr>
                            <td colspan="4" class="border border-gray-300 px-3 py-2 text-right uppercase">Total</td>
                            <td class="border border-gray-300 px-3 py-2 text-right">{{ number_format($summary['total_pagu'], 0, ',', '.') }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-right">{{ number_format($summary['total_realisasi'], 0, ',', '.') }}</td>
                            <td class="border border-gray-300 px-3 py-2 text-center">{{ $summary['persentase_keuangan'] }}%</td>
                            <td class="border border-gray-300 px-3 py-2 text-center">{{ $summary['rata_progres_fisik'] }}%</td>
                            <td class="border border-gray-300 px-3 py-2"></td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>

        {{-- Grouped by Kecamatan/Desa (If requested in specific reports) --}}
        @if(!$desaId && $perKecamatan->isNotEmpty() && $jenis == 'evaluasi_kinerja')
            <div class="print-page-break print-break-inside-avoid">
                <h4 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Kinerja per Kecamatan</h4>
                <table class="w-full text-left border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 text-xs text-gray-700 uppercase font-semibold">
                            <th class="border border-gray-300 px-3 py-2">Kecamatan</th>
                            <th class="border border-gray-300 px-3 py-2 text-center">Jml Kegiatan</th>
                            <th class="border border-gray-300 px-3 py-2 text-right">Pagu</th>
                            <th class="border border-gray-300 px-3 py-2 text-right">Realisasi</th>
                            <th class="border border-gray-300 px-3 py-2 text-center">% Keuangan</th>
                            <th class="border border-gray-300 px-3 py-2 text-center">Rata² Fisik</th>
                            <th class="border border-gray-300 px-3 py-2 text-center">Selesai / Terlambat</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-gray-700">
                        @foreach($perKecamatan as $kec)
                            <tr>
                                <td class="border border-gray-300 px-3 py-2 font-bold">{{ $kec['nama'] }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ $kec['total_kegiatan'] }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-right">{{ number_format($kec['total_pagu'], 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-right">{{ number_format($kec['total_realisasi'], 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ $kec['persentase_keuangan'] }}%</td>
                                <td class="border border-gray-300 px-3 py-2 text-center font-bold">{{ $kec['rata_progres'] }}%</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <span class="text-green-600">{{ $kec['selesai'] }}</span> / 
                                    <span class="text-red-600">{{ $kec['terlambat'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- TTD Section --}}
        <div class="mt-20 flex justify-end print-break-inside-avoid">
            <div class="text-center w-64">
                <p class="text-sm text-gray-700 mb-16">Soreang, {{ now()->isoFormat('D MMMM Y') }}<br>Mengetahui,</p>
                <div class="border-b border-gray-900 mb-1"></div>
                <p class="text-sm font-bold text-gray-900 uppercase">Pejabat Berwenang</p>
                <p class="text-xs text-gray-600">NIP. ........................................</p>
            </div>
        </div>

    </div>

</body>
</html>
