<?php

namespace App\Http\Controllers;

use App\Models\Monev;
use App\Models\Desa;
use App\Models\Anggaran;
use App\Models\Kegiatan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonevController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Hanya bisa diakses Kabupaten & Kecamatan
        if ($user->isDesa()) {
            abort(403, 'Akses ditolak.');
        }

        $query = Monev::with(['desa', 'kegiatan', 'user'])->latest();

        if ($user->isKecamatan()) {
            $query->whereHas('desa', function($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        }

        $monevs = $query->paginate(15);

        // Calculate Ranking (Average score per desa)
        $desaRankingQuery = Desa::withCount('monevs')
            ->withAvg('monevs as rata_rata_skor', 'skor_total');
            
        if ($user->isKecamatan()) {
            $desaRankingQuery->where('kecamatan_id', $user->kecamatan_id);
        }
        
        $desaRankings = $desaRankingQuery->having('monevs_count', '>', 0)
            ->orderByDesc('rata_rata_skor')
            ->get();

        $terbaik = $desaRankings->first();
        $perluPembinaan = $desaRankings->filter(function($desa) {
            return $desa->rata_rata_skor < 75;
        });

        return view('monev.index', compact('monevs', 'desaRankings', 'terbaik', 'perluPembinaan'));
    }

    public function wizard(Request $request)
    {
        $user = auth()->user();
        if ($user->isDesa()) abort(403);

        $desas = $user->isKecamatan() 
            ? Desa::where('kecamatan_id', $user->kecamatan_id)->get()
            : Desa::all();

        $selectedDesa = $request->desa_id ? Desa::find($request->desa_id) : null;
        $anggarans = collect();
        $kegiatans = collect();

        if ($selectedDesa) {
            $anggarans = Anggaran::with('sumberDana')->where('desa_id', $selectedDesa->id)->get();
        }

        $selectedAnggaran = $request->anggaran_id ? Anggaran::find($request->anggaran_id) : null;
        if ($selectedAnggaran && $selectedDesa) {
            $kegiatans = Kegiatan::with('monev')
                ->where('desa_id', $selectedDesa->id)
                ->where('sumber_dana_id', $selectedAnggaran->sumber_dana_id)
                ->where('tahun_anggaran', $selectedAnggaran->tahun_anggaran)
                ->get();
        }

        return view('monev.wizard', compact('desas', 'selectedDesa', 'anggarans', 'selectedAnggaran', 'kegiatans'));
    }

    public function create(Kegiatan $kegiatan)
    {
        $user = auth()->user();
        if ($user->isDesa()) abort(403);

        // Cek jika sudah ada monev
        $monev = Monev::where('kegiatan_id', $kegiatan->id)->first();
        if ($monev) {
            return redirect()->route('monev.show', $monev)->with('info', 'Monev untuk kegiatan ini sudah pernah dilakukan.');
        }

        return view('monev.form', compact('kegiatan'));
    }

    public function store(Request $request, Kegiatan $kegiatan)
    {
        $user = auth()->user();
        if ($user->isDesa()) abort(403);

        // Evaluasi Perencanaan
        $perencanaan = $request->input('perencanaan', []);
        $skorPerencanaan = (count($perencanaan) / 4) * 100; // Misal 4 item wajib

        // Evaluasi Keuangan
        $keuangan = $request->input('keuangan', []);
        $skorKeuangan = (count($keuangan) / 5) * 100; // Misal 5 item wajib

        // Evaluasi Pelaksanaan
        $pelaksanaan = $request->input('pelaksanaan', []);
        $skorPelaksanaan = (count($pelaksanaan) / 4) * 100;

        // Evaluasi Fisik
        $fisik = $request->input('fisik', []);
        if (in_array('bukan_fisik', $fisik)) {
            $skorFisik = 100;
        } else {
            $skorFisik = (count($fisik) / 4) * 100;
        }

        // Evaluasi Pelaporan
        $pelaporan = $request->input('pelaporan', []);
        $skorPelaporan = (count($pelaporan) / 3) * 100;

        // Skor Total
        $skorTotal = ($skorPerencanaan + $skorKeuangan + $skorPelaksanaan + $skorFisik + $skorPelaporan) / 5;

        $monev = Monev::create([
            'desa_id' => $kegiatan->desa_id,
            'kegiatan_id' => $kegiatan->id,
            'user_id' => $user->id,
            'tanggal_monev' => now(),
            
            'aspek_perencanaan' => $perencanaan,
            'aspek_keuangan' => $keuangan,
            'aspek_pelaksanaan' => $pelaksanaan,
            'aspek_fisik' => $fisik,
            'aspek_pelaporan' => $pelaporan,

            'skor_perencanaan' => min($skorPerencanaan, 100),
            'skor_keuangan' => min($skorKeuangan, 100),
            'skor_pelaksanaan' => min($skorPelaksanaan, 100),
            'skor_fisik' => min($skorFisik, 100),
            'skor_pelaporan' => min($skorPelaporan, 100),
            'skor_total' => min($skorTotal, 100),
        ]);

        return redirect()->route('monev.show', $monev)->with('success', 'Hasil Monev berhasil disimpan!');
    }

    public function show(Monev $monev)
    {
        $monev->load(['desa.kecamatan', 'kegiatan', 'user']);
        return view('monev.show', compact('monev'));
    }

    public function generateAi(Monev $monev)
    {
        $apiKey = Setting::get('openai_api_key');
        if (!$apiKey) {
            return redirect()->back()->with('error', 'API Key OpenAI belum dikonfigurasi. Hubungi Admin.');
        }

        try {
            $prompt = $this->buildPrompt($monev);

            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah AI Asisten Monitoring dan Evaluasi (Monev) Desa. Analisa data yang diberikan, dan berikan Insight Naratif (kelebihan, kekurangan) dan Temuan Prioritas (apa yang harus segera diperbaiki).'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Gagal terhubung ke OpenAI: ' . $response->json('error.message', 'Unknown Error'));
            }

            $aiText = $response->json('choices.0.message.content');

            $monev->update([
                'ai_insight' => $aiText
            ]);

            return redirect()->back()->with('success', 'AI Insight berhasil di-generate.');

        } catch (\Exception $e) {
            Log::error('AI Monev Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses AI: ' . $e->getMessage());
        }
    }

    private function buildPrompt(Monev $monev): string
    {
        $kegiatan = $monev->kegiatan->nama_kegiatan;
        $desa = $monev->desa->nama;
        
        return <<<PROMPT
Tolong buatkan Insight Monev untuk kegiatan "{$kegiatan}" di Desa "{$desa}".
Berikut adalah rincian skor hasil monitoring lapangan:
- Skor Perencanaan: {$monev->skor_perencanaan}
- Skor Administrasi Keuangan: {$monev->skor_keuangan}
- Skor Pelaksanaan: {$monev->skor_pelaksanaan}
- Skor Fisik: {$monev->skor_fisik}
- Skor Pelaporan: {$monev->skor_pelaporan}
- Skor Total: {$monev->skor_total}

Detail kelengkapan dokumen/checklist (JSON Format):
Perencanaan: {$this->safeJson($monev->aspek_perencanaan)}
Keuangan: {$this->safeJson($monev->aspek_keuangan)}
Pelaksanaan: {$this->safeJson($monev->aspek_pelaksanaan)}
Fisik: {$this->safeJson($monev->aspek_fisik)}
Pelaporan: {$this->safeJson($monev->aspek_pelaporan)}

Instruksi:
1. Berikan paragraf ringkasan kinerja desa dalam kegiatan ini.
2. Buat daftar bullet points untuk "Kelebihan / Hal Positif".
3. Buat daftar bullet points untuk "Temuan / Area Perbaikan" berdasarkan skor yang rendah atau dokumen yang tidak dicentang.
4. Gunakan bahasa Indonesia formal dan profesional khas instansi pemerintah.
Format output menggunakan markdown.
PROMPT;
    }

    private function safeJson($data) {
        return json_encode($data ?? []) ?: '[]';
    }
}
