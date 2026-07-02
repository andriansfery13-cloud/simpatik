<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\SumberDana;
use App\Models\Anggaran;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimpatikSeeder extends Seeder
{
    public function run(): void
    {
        // === SUMBER DANA ===
        $sumberDana = [
            ['kode' => 'DD', 'nama' => 'Dana Desa', 'deskripsi' => 'Dana yang bersumber dari APBN untuk desa'],
            ['kode' => 'ADD', 'nama' => 'Alokasi Dana Desa', 'deskripsi' => 'Alokasi dana dari APBD Kabupaten'],
            ['kode' => 'APBD-KAB', 'nama' => 'APBD Kabupaten', 'deskripsi' => 'Anggaran Pendapatan dan Belanja Daerah Kabupaten'],
            ['kode' => 'APBD-PROV', 'nama' => 'APBD Provinsi', 'deskripsi' => 'Anggaran Pendapatan dan Belanja Daerah Provinsi'],
            ['kode' => 'BK', 'nama' => 'Bantuan Keuangan', 'deskripsi' => 'Bantuan keuangan dari pemerintah daerah'],
            ['kode' => 'CSR', 'nama' => 'CSR dan Hibah', 'deskripsi' => 'Corporate Social Responsibility dan hibah'],
            ['kode' => 'PAD', 'nama' => 'Pendapatan Asli Desa', 'deskripsi' => 'Pendapatan yang berasal dari desa sendiri'],
        ];

        foreach ($sumberDana as $sd) {
            SumberDana::create($sd);
        }

        // === KECAMATAN ===
        $kecamatans = [
            ['kode' => '3204010', 'nama' => 'Soreang', 'camat' => 'H. Asep Surahman, S.Sos., M.Si.', 'latitude' => -6.9956, 'longitude' => 107.5139],
            ['kode' => '3204020', 'nama' => 'Katapang', 'camat' => 'Drs. Agus Hermawan', 'latitude' => -6.9567, 'longitude' => 107.5231],
            ['kode' => '3204030', 'nama' => 'Margaasih', 'camat' => 'Iman Sulaeman, S.IP.', 'latitude' => -6.9462, 'longitude' => 107.5589],
            ['kode' => '3204040', 'nama' => 'Margahayu', 'camat' => 'Drs. Hendra Gunawan', 'latitude' => -6.9523, 'longitude' => 107.5801],
            ['kode' => '3204050', 'nama' => 'Dayeuhkolot', 'camat' => 'Ir. Bambang Sutrisno', 'latitude' => -6.9734, 'longitude' => 107.6123],
            ['kode' => '3204060', 'nama' => 'Bojongsoang', 'camat' => 'Ahmad Fauzi, S.Sos.', 'latitude' => -6.9876, 'longitude' => 107.6345],
        ];

        foreach ($kecamatans as $kec) {
            Kecamatan::create($kec);
        }

        // === DESA (Kecamatan Soreang) ===
        $soreang = Kecamatan::where('nama', 'Soreang')->first();
        $desasSoreang = [
            ['kode' => '3204010001', 'nama' => 'Pamekaran', 'kepala_desa' => 'H. Dede Kosasih', 'latitude' => -6.9901, 'longitude' => 107.5102, 'jumlah_penduduk' => 12500, 'luas_wilayah' => 3.50],
            ['kode' => '3204010002', 'nama' => 'Sumbersari', 'kepala_desa' => 'Agus Rahmat', 'latitude' => -6.9923, 'longitude' => 107.5156, 'jumlah_penduduk' => 9800, 'luas_wilayah' => 2.80],
            ['kode' => '3204010003', 'nama' => 'Rancamanyar', 'kepala_desa' => 'Dedi Mulyadi', 'latitude' => -6.9945, 'longitude' => 107.5078, 'jumlah_penduduk' => 11200, 'luas_wilayah' => 4.10],
            ['kode' => '3204010004', 'nama' => 'Katapang', 'kepala_desa' => 'Ade Suryana', 'latitude' => -6.9878, 'longitude' => 107.5201, 'jumlah_penduduk' => 8700, 'luas_wilayah' => 2.50],
            ['kode' => '3204010005', 'nama' => 'Parungserab', 'kepala_desa' => 'Nana Supriatna', 'latitude' => -6.9989, 'longitude' => 107.5089, 'jumlah_penduduk' => 7600, 'luas_wilayah' => 3.20],
            ['kode' => '3204010006', 'nama' => 'Babakan Sari', 'kepala_desa' => 'Ujang Supriadi', 'latitude' => -6.9967, 'longitude' => 107.5167, 'jumlah_penduduk' => 10300, 'luas_wilayah' => 3.80],
            ['kode' => '3204010007', 'nama' => 'Mekarjaya', 'kepala_desa' => 'Iwan Setiawan', 'latitude' => -6.9834, 'longitude' => 107.5234, 'jumlah_penduduk' => 6900, 'luas_wilayah' => 2.10],
            ['kode' => '3204010008', 'nama' => 'Sukamaju', 'kepala_desa' => 'Cecep Rustandi', 'latitude' => -6.9912, 'longitude' => 107.5312, 'jumlah_penduduk' => 8100, 'luas_wilayah' => 2.90],
            ['kode' => '3204010009', 'nama' => 'Cingcin', 'kepala_desa' => 'Edi Suprianto', 'latitude' => -7.0012, 'longitude' => 107.5045, 'jumlah_penduduk' => 5400, 'luas_wilayah' => 4.50],
            ['kode' => '3204010010', 'nama' => 'Panyirapan', 'kepala_desa' => 'Dadan Ramdani', 'latitude' => -6.9856, 'longitude' => 107.5278, 'jumlah_penduduk' => 7200, 'luas_wilayah' => 3.10],
            ['kode' => '3204010011', 'nama' => 'Karamatmulya', 'kepala_desa' => 'Asep Kurnia', 'latitude' => -6.9978, 'longitude' => 107.5198, 'jumlah_penduduk' => 6100, 'luas_wilayah' => 2.60],
            ['kode' => '3204010012', 'nama' => 'Sukanagara', 'kepala_desa' => 'Tedi Firmansyah', 'latitude' => -7.0034, 'longitude' => 107.5123, 'jumlah_penduduk' => 5800, 'luas_wilayah' => 3.40],
        ];

        foreach ($desasSoreang as $d) {
            $d['kecamatan_id'] = $soreang->id;
            Desa::create($d);
        }

        // Add some desas for other kecamatans
        $katapang = Kecamatan::where('nama', 'Katapang')->first();
        $desasKatapang = [
            ['kode' => '3204020001', 'nama' => 'Pangauban', 'kepala_desa' => 'Rohman', 'jumlah_penduduk' => 9200],
            ['kode' => '3204020002', 'nama' => 'Cilampeni', 'kepala_desa' => 'Andi Kurniawan', 'jumlah_penduduk' => 8500],
            ['kode' => '3204020003', 'nama' => 'Gandasari', 'kepala_desa' => 'Heri Suherman', 'jumlah_penduduk' => 7800],
        ];
        foreach ($desasKatapang as $d) {
            $d['kecamatan_id'] = $katapang->id;
            Desa::create($d);
        }

        // === USERS ===
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simpatik.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'jabatan' => 'System Administrator',
            'is_active' => true,
        ]);

        // Kabupaten level
        User::create([
            'name' => 'H. Dadang Supriatna',
            'email' => 'bupati@simpatik.go.id',
            'password' => Hash::make('password'),
            'role' => 'bupati',
            'jabatan' => 'Bupati Bandung',
            'is_active' => true,
        ]);

        // Kecamatan level - Soreang
        User::create([
            'name' => 'H. Asep Surahman, S.Sos., M.Si.',
            'email' => 'camat.soreang@simpatik.go.id',
            'password' => Hash::make('password'),
            'role' => 'camat',
            'kecamatan_id' => $soreang->id,
            'jabatan' => 'Camat Soreang',
            'nip' => '196805121990031005',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Operator Soreang',
            'email' => 'operator.soreang@simpatik.go.id',
            'password' => Hash::make('password'),
            'role' => 'operator_kecamatan',
            'kecamatan_id' => $soreang->id,
            'jabatan' => 'Operator Kecamatan',
            'is_active' => true,
        ]);

        // Desa level
        $pamekaran = Desa::where('nama', 'Pamekaran')->first();
        User::create([
            'name' => 'H. Dede Kosasih',
            'email' => 'kades.pamekaran@simpatik.go.id',
            'password' => Hash::make('password'),
            'role' => 'kepala_desa',
            'kecamatan_id' => $soreang->id,
            'desa_id' => $pamekaran->id,
            'jabatan' => 'Kepala Desa Pamekaran',
            'is_active' => true,
        ]);

        // === KEGIATAN (Activities) ===
        $dd = SumberDana::where('kode', 'DD')->first();
        $add = SumberDana::where('kode', 'ADD')->first();
        $apbdKab = SumberDana::where('kode', 'APBD-KAB')->first();
        $bk = SumberDana::where('kode', 'BK')->first();

        $desas = Desa::where('kecamatan_id', $soreang->id)->get();
        $tahun = 2026;

        $kegiatanTemplates = [
            ['nama' => 'Pembangunan Jalan Desa', 'pagu' => 350000000, 'progres' => 35, 'status' => 'berjalan', 'mulai' => '2026-02-01', 'selesai' => '2026-08-30'],
            ['nama' => 'Drainase Desa', 'pagu' => 180000000, 'progres' => 40, 'status' => 'berjalan', 'mulai' => '2026-03-01', 'selesai' => '2026-07-31'],
            ['nama' => 'Rabat Beton Desa', 'pagu' => 120000000, 'progres' => 45, 'status' => 'berjalan', 'mulai' => '2026-01-15', 'selesai' => '2026-06-30'],
            ['nama' => 'Pembangunan Posyandu', 'pagu' => 85000000, 'progres' => 100, 'status' => 'selesai', 'mulai' => '2026-01-10', 'selesai' => '2026-04-30'],
            ['nama' => 'Rehab Gedung PAUD', 'pagu' => 75000000, 'progres' => 92, 'status' => 'selesai', 'mulai' => '2026-02-15', 'selesai' => '2026-05-31'],
            ['nama' => 'Pembangunan MCK', 'pagu' => 60000000, 'progres' => 15, 'status' => 'terlambat', 'mulai' => '2026-01-01', 'selesai' => '2026-04-30'],
            ['nama' => 'Pembangunan Talud Sungai', 'pagu' => 250000000, 'progres' => 65, 'status' => 'berjalan', 'mulai' => '2026-02-01', 'selesai' => '2026-09-30'],
            ['nama' => 'Pengaspalan Jalan RT', 'pagu' => 95000000, 'progres' => 80, 'status' => 'berjalan', 'mulai' => '2026-03-15', 'selesai' => '2026-07-15'],
        ];

        $sumberDanas = [$dd, $add, $apbdKab, $bk];

        foreach ($desas as $index => $desa) {
            // Create anggaran for each desa
            foreach ($sumberDanas as $sIdx => $sd) {
                Anggaran::create([
                    'desa_id' => $desa->id,
                    'sumber_dana_id' => $sd->id,
                    'tahun_anggaran' => $tahun,
                    'pagu' => rand(200, 800) * 1000000,
                    'realisasi' => rand(100, 500) * 1000000,
                    'status_earmark' => $sIdx % 2 === 0 ? 'earmarked' : 'non-earmarked',
                ]);
            }

            // Create 3-5 kegiatan per desa
            $numKegiatan = rand(3, 5);
            for ($i = 0; $i < $numKegiatan; $i++) {
                $template = $kegiatanTemplates[$i % count($kegiatanTemplates)];
                $randomSd = $sumberDanas[array_rand($sumberDanas)];
                $pagu = $template['pagu'] + rand(-20, 20) * 1000000;
                $progres = min(100, max(0, $template['progres'] + rand(-15, 15)));
                $realisasi = $pagu * ($progres / 100) * (rand(80, 120) / 100);

                $status = $template['status'];
                if ($progres >= 100) $status = 'selesai';
                elseif ($progres < 20 && $template['status'] === 'terlambat') $status = 'terlambat';

                Kegiatan::create([
                    'desa_id' => $desa->id,
                    'sumber_dana_id' => $randomSd->id,
                    'nama_kegiatan' => $template['nama'] . ' ' . $desa->nama,
                    'deskripsi' => 'Kegiatan ' . strtolower($template['nama']) . ' di wilayah ' . $desa->nama,
                    'lokasi' => 'Desa ' . $desa->nama . ', Kec. Soreang',
                    'latitude' => $desa->latitude ? $desa->latitude + (rand(-10, 10) / 10000) : null,
                    'longitude' => $desa->longitude ? $desa->longitude + (rand(-10, 10) / 10000) : null,
                    'pagu_anggaran' => $pagu,
                    'realisasi_anggaran' => round($realisasi),
                    'progres_fisik' => $progres,
                    'tanggal_mulai' => $template['mulai'],
                    'tanggal_selesai' => $template['selesai'],
                    'status' => $status,
                    'pelaksana' => 'CV. Karya ' . $desa->nama,
                    'penanggung_jawab' => $desa->kepala_desa,
                    'tahun_anggaran' => $tahun,
                ]);
            }
        }
    }
}
