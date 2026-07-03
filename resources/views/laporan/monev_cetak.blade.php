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
            margin-bottom: 5px;
        }
        .kop-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }
        .kop-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }
        .kop-logo-td {
            width: 80px;
            padding-right: 10px;
        }
        .kop-logo {
            width: 70px;
            height: 70px;
        }
        .kop-text {
            text-align: center;
        }
        .kop-pemerintah { font-size: 14pt; font-weight: bold; }
        .kop-instansi { font-size: 16pt; font-weight: bold; }
        .kop-alamat { font-size: 10pt; }
        .kop-line {
            border: none;
            border-top: 3px solid #000;
            margin: 0 0 2px 0;
            padding: 0;
        }
        .kop-line-thin {
            border: none;
            border-top: 1px solid #000;
            margin: 0 0 15px 0;
            padding: 0;
        }

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
        
        .action-bar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 9999;
            font-family: Arial, sans-serif;
        }
        .action-btn {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-pdf { background: #4f46e5; }
        .btn-pdf:hover { background: #4338ca; }
        .btn-word { background: #2563eb; }
        .btn-word:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="action-bar">
        <button class="action-btn btn-pdf" onclick="window.print()">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak PDF
        </button>
        <button class="action-btn btn-word" onclick="saveToWord()">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Simpan ke Word (.doc)
        </button>
    </div>

    <script>
    function saveToWord() {
        // Clone the body content without the action bar
        var content = document.body.cloneNode(true);
        var actionBar = content.querySelector('.action-bar');
        if (actionBar) actionBar.remove();

        // Get all styles
        var styles = '';
        var styleSheets = document.querySelectorAll('style');
        styleSheets.forEach(function(s) {
            styles += s.innerHTML;
        });

        var html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
                  xmlns:w="urn:schemas-microsoft-com:office:word"
                  xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset="UTF-8">
                <!--[if gte mso 9]>
                <xml>
                    <w:WordDocument>
                        <w:View>Print</w:View>
                        <w:Zoom>100</w:Zoom>
                        <w:DoNotOptimizeForBrowser/>
                    </w:WordDocument>
                </xml>
                <![endif]-->
                <style>${styles}</style>
            </head>
            <body>${content.innerHTML}</body>
            </html>`;

        var blob = new Blob(['\ufeff', html], { type: 'application/msword' });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'Berita_Acara_Monev_{{ str_replace(" ", "_", $anggaran->desa->nama) }}_{{ $anggaran->tahun_anggaran }}.doc';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
    </script>

    {{-- Kop Surat --}}
    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                @if($kopSurat && $kopSurat->logo_path)
                <td class="kop-logo-td">
                    <img src="{{ asset('storage/' . $kopSurat->logo_path) }}" alt="Logo" class="kop-logo" width="70" height="70" style="width:70px;height:70px;">
                </td>
                @endif
                <td class="kop-text">
                    <div class="kop-pemerintah uppercase">{{ $kopSurat->pemerintah ?? 'PEMERINTAH KABUPATEN BANDUNG' }}</div>
                    <div class="kop-instansi uppercase">{{ $kopSurat->instansi ?? 'KECAMATAN CIPARAY' }}</div>
                    <div class="kop-alamat">{{ $kopSurat->alamat ?? 'Jalan Pamageran No. 2 Ciparay Telp. (022) 5950372' }}</div>
                    <div class="kop-alamat">{{ $kopSurat->kontak ?? 'Email: kec.ciparay@bandungkab.go.id' }}</div>
                </td>
            </tr>
        </table>
    </div>
    <hr class="kop-line">
    <hr class="kop-line-thin">

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
                            @php
                                // Daftar item Administrasi Keuangan
                                $keuanganItems = [
                                    'bku' => 'Buku Kas Umum (BKU)',
                                    'buku_bank' => 'Buku Bank & Rekening Koran',
                                    'buku_pajak' => 'Buku Pajak',
                                    'spj' => 'Register SPJ',
                                    'bukti_transfer' => 'Bukti Pengeluaran / Transfer Sah',
                                ];
                                // Daftar item Pelaksanaan Kegiatan
                                $pelaksanaanItems = [
                                    'sk_tpk' => 'SK TPK',
                                    'rab' => 'RAB & Gambar Teknis',
                                    'kontrak' => 'Kontrak Kerja / Surat Pesanan',
                                    'dokumentasi' => 'Dokumentasi Pelaksanaan (0%, 50%, 100%)',
                                ];

                                $aspekKeuangan = $k->monev->aspek_keuangan ?? [];
                                $aspekPelaksanaan = $k->monev->aspek_pelaksanaan ?? [];

                                // Cari item yang TIDAK dicentang
                                $kekuranganKeuangan = [];
                                foreach ($keuanganItems as $key => $label) {
                                    if (!in_array($key, $aspekKeuangan)) {
                                        $kekuranganKeuangan[] = $label;
                                    }
                                }
                                $kekuranganPelaksanaan = [];
                                foreach ($pelaksanaanItems as $key => $label) {
                                    if (!in_array($key, $aspekPelaksanaan)) {
                                        $kekuranganPelaksanaan[] = $label;
                                    }
                                }

                                $isLengkap = empty($kekuranganKeuangan) && empty($kekuranganPelaksanaan);
                            @endphp
                            <ul>
                                <li>Kegiatan Sudah Dilaksanakan</li>
                                @if($isLengkap)
                                    <li>LPJ Sudah Lengkap</li>
                                @else
                                    <li><b>LPJ Belum Lengkap</b></li>
                                    @if(!empty($kekuranganKeuangan))
                                        <li>Administrasi Keuangan belum lengkap:
                                            <ul>
                                                @foreach($kekuranganKeuangan as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                    @if(!empty($kekuranganPelaksanaan))
                                        <li>Pelaksanaan Kegiatan belum lengkap:
                                            <ul>
                                                @foreach($kekuranganPelaksanaan as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
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
                    <th style="width: 50%">{{ str_starts_with(strtoupper($anggaran->desa->nama), 'DESA') ? 'KEPALA ' . strtoupper($anggaran->desa->nama) : 'KEPALA DESA ' . strtoupper($anggaran->desa->nama) }}</th>
                    <th style="width: 50%">{{ $camat ? 'CAMAT' : '' }}</th>
                </tr>
                <tr>
                    <td style="height: 80px; vertical-align: bottom; text-align: center">
                        <b>({{ strtoupper($kepalaDesa) }})</b>
                    </td>
                    <td style="height: 80px; vertical-align: bottom; text-align: center">
                        @if($camat)
                        <b>({{ strtoupper($camat) }})</b>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
