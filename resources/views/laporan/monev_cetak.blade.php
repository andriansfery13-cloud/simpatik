<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Monev - {{ $anggaran->desa->nama }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-8 { margin-top: 2rem; }
        .w-full { width: 100%; }
        
        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .kop-surat::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            border-bottom: 1px solid #000;
        }
        .kop-logo {
            width: 80px;
            height: auto;
            position: absolute;
            left: 0;
        }
        .kop-text {
            width: 100%;
            text-align: center;
            padding: 0 90px;
        }
        .kop-pemerintah { font-size: 14pt; font-weight: bold; }
        .kop-instansi { font-size: 16pt; font-weight: bold; }
        .kop-alamat { font-size: 10pt; }

        /* Judul */
        .judul-surat {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px 8px;
            vertical-align: top;
        }
        th {
            text-align: center;
            font-weight: bold;
            background-color: #f3f3f3;
        }

        /* Tanda Tangan */
        .ttd-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 40px;
            text-align: center;
        }
        .ttd-box {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        /* List */
        ol { margin-top: 0; padding-left: 20px; }
        ul { margin-top: 0; padding-left: 20px; margin-bottom: 0; }

        @media print {
            body { margin: 0; }
            button { display: none; }
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Cetak PDF (Ctrl+P)</button>

    {{-- Kop Surat --}}
    <div class="kop-surat">
        @if($kopSurat && $kopSurat->logo_path)
            <img src="{{ asset('storage/' . $kopSurat->logo_path) }}" alt="Logo" class="kop-logo">
        @endif
        <div class="kop-text">
            <div class="kop-pemerintah uppercase">{{ $kopSurat->pemerintah ?? 'PEMERINTAH KABUPATEN BANDUNG' }}</div>
            <div class="kop-instansi uppercase">{{ $kopSurat->instansi ?? 'KECAMATAN CIPARAY' }}</div>
            <div class="kop-alamat">{{ $kopSurat->alamat ?? 'Jalan Pamageran No. 2 Ciparay Telp. (022) 5950372' }}</div>
            <div class="kop-alamat">{{ $kopSurat->kontak ?? 'Email: kec.ciparay@bandungkab.go.id' }}</div>
        </div>
    </div>

    {{-- Judul --}}
    <div class="judul-surat">
        <u>BERITA ACARA</u><br>
        MONITORING DAN EVALUASI {{ strtoupper($anggaran->sumberDana->nama) }} {{ $tahap ? $tahap . ' ' : '' }}TAHUN {{ $anggaran->tahun_anggaran }}<br>
        {{ str_starts_with(strtoupper($anggaran->desa->nama), 'DESA') ? strtoupper($anggaran->desa->nama) : 'DESA ' . strtoupper($anggaran->desa->nama) }}<br>
        {{ $kopSurat && $kopSurat->instansi ? strtoupper($kopSurat->instansi) : 'KECAMATAN ' . strtoupper($anggaran->desa->kecamatan->nama) }}
    </div>

    {{-- Pembuka --}}
    @php
        $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $bulan = ['1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April', '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus', '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
        $now = now();
    @endphp
    <p>
        Pada hari ini <b>{{ $hari[$now->format('l')] }}</b> Tanggal <b>{{ $now->format('d') }}</b> Bulan <b>{{ $bulan[$now->format('n')] }}</b> Tahun <b>{{ $now->format('Y') }}</b>, kami TIM Pendamping Kecamatan telah melaksanakan Pembinaan / Monitoring dan Evaluasi {{ $anggaran->sumberDana->nama }} {{ $tahap ? $tahap . ' ' : '' }}Tahun {{ $anggaran->tahun_anggaran }} dengan hasil sebagai berikut:
    </p>

    <ol>
        <li>Anggaran {{ $anggaran->sumberDana->nama }} {{ $tahap ? $tahap . ' ' : '' }}Tahun {{ $anggaran->tahun_anggaran }} sebesar <b>Rp {{ number_format($anggaran->pagu, 0, ',', '.') }}</b></li>
        <li>Kegiatan yang dilaksanakan antara lain:</li>
    </ol>

    {{-- Tabel 1: Kegiatan --}}
    <table>
        <thead>
            <tr>
                <th style="width: 5%">NO</th>
                <th style="width: 65%">NAMA KEGIATAN</th>
                <th style="width: 30%">ANGGARAN (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAnggaran = 0; @endphp
            @foreach($kegiatans as $index => $k)
                @php $totalAnggaran += $k->pagu_anggaran; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $k->nama_kegiatan }}</td>
                    <td style="text-align: right">Rp {{ number_format($k->pagu_anggaran, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: center">Jumlah</th>
                <th style="text-align: right">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <ol start="3">
        <li>Kelengkapan laporan pertanggungjawaban {{ $anggaran->sumberDana->nama }} {{ $tahap ? $tahap . ' ' : '' }}Tahun {{ $anggaran->tahun_anggaran }} antara lain:</li>
    </ol>

    {{-- Tabel 2: Kelengkapan --}}
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 45%">Uraian</th>
                <th style="width: 50%">Kelengkapan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatans as $index => $k)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $k->nama_kegiatan }}</td>
                    <td>
                        @if($k->monev)
                            <ul>
                                <li>Kegiatan sudah dilaksanakan</li>
                                @if($k->monev->catatan_saran)
                                <li>Kekurangan: {{ $k->monev->catatan_saran }}</li>
                                @else
                                <li>LPJ Lengkap</li>
                                @endif
                            </ul>
                        @else
                            <ul>
                                <li>Kegiatan belum dilaksanakan</li>
                            </ul>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <ol start="4">
        <li><b>Kesimpulan</b>
            <ul>
                @php
                    $monevsCount = $kegiatans->filter(fn($k) => $k->monev !== null)->count();
                    $avgScore = $monevsCount > 0 ? $kegiatans->filter(fn($k) => $k->monev !== null)->avg('monev.skor_total') : 0;
                @endphp
                @if($monevsCount > 0)
                <li>Secara umum pelaksanaan kegiatan dikategorikan berjalan dengan {{ $avgScore >= 75 ? 'Baik' : 'Cukup' }}.</li>
                <li>Rata-rata kesesuaian dokumen administrasi dan fisik mencapai {{ number_format($avgScore, 1) }}%.</li>
                @else
                <li>Belum ada kegiatan yang selesai dievaluasi pada anggaran ini.</li>
                @endif
            </ul>
        </li>
        <li><b>Tindak Lanjut / Saran / Rekomendasi</b>
            <ul>
                <li>Agar Kepala Desa menginstruksikan kepada Tim Pengelola Kegiatan untuk melengkapi kekurangan administrasi LPJ (jika ada) sesuai dengan peraturan yang berlaku.</li>
                <li>Meningkatkan pengawasan mutu fisik lapangan pada tahap berikutnya.</li>
            </ul>
        </li>
    </ol>

    {{-- Tabel Tim Pembina --}}
    <div class="mt-8">
        <table>
            <thead>
                <tr>
                    <th colspan="3" class="uppercase">TIM PEMBINA KECAMATAN</th>
                </tr>
                <tr>
                    <th style="width: 40%">NAMA</th>
                    <th style="width: 30%">JABATAN</th>
                    <th style="width: 30%">TANDA TANGAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timPembina as $tim)
                    <tr>
                        <td>{{ $tim['nama'] }}</td>
                        <td class="text-center">{{ $tim['jabatan'] }}</td>
                        <td style="height: 40px;"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tabel Mengetahui --}}
    <div class="mt-4">
        <table>
            <thead>
                <tr>
                    <th colspan="2" class="uppercase">MENGETAHUI</th>
                </tr>
                <tr>
                    <th style="width: 50%">KETUA BPD</th>
                    <th style="width: 50%">KETUA LPMD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 80px; vertical-align: bottom; text-align: center">
                        <b>({{ strtoupper($ketuaBpd) }})</b>
                    </td>
                    <td style="height: 80px; vertical-align: bottom; text-align: center">
                        <b>({{ strtoupper($ketuaLpmd) }})</b>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="uppercase">{{ str_starts_with(strtoupper($anggaran->desa->nama), 'DESA') ? 'KEPALA ' . strtoupper($anggaran->desa->nama) : 'KEPALA DESA ' . strtoupper($anggaran->desa->nama) }}</th>
                </tr>
                <tr>
                    <td colspan="2" style="height: 80px; vertical-align: bottom; text-align: center; border:none;">
                        <b>({{ strtoupper($kepalaDesa) }})</b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    @if($camat)
    <div class="ttd-grid" style="grid-template-columns: 1fr;">
        <div class="ttd-box">
            <span>CAMAT</span>
            <span class="ttd-name">{{ strtoupper($camat) }}</span>
        </div>
    </div>
    @endif

</body>
</html>
