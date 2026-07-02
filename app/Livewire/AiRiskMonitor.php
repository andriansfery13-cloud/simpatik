<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Kegiatan;
use App\Models\Desa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiRiskMonitor extends Component
{
    public string $riskResult = '';
    public bool $riskLoading = false;
    public string $riskError = '';

    protected function callOpenAI(string $systemPrompt, string $userPrompt, int $maxTokens = 2000): array
    {
        $apiKey = Setting::get('openai_api_key');

        if (!$apiKey) {
            return ['error' => 'API Key OpenAI belum diatur. Silakan atur di menu Pengaturan Sistem.'];
        }

        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 0.5,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->successful()) {
                return ['content' => $response->json('choices.0.message.content')];
            } else {
                $errorMsg = $response->json('error.message') ?? 'Terjadi kesalahan API.';
                return ['error' => 'Gagal: ' . $errorMsg];
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Risk Monitor Error: ' . $e->getMessage());
            return ['error' => 'Kesalahan sistem: ' . $e->getMessage()];
        }
    }

    public function analyzeRisk()
    {
        $this->riskLoading = true;
        $this->riskError = '';
        $this->riskResult = '';

        $kegiatans = Kegiatan::with('desa.kecamatan')->get();
        $desas = Desa::with('kecamatan')->withCount('kegiatans')->get();

        // Build detailed data for risk analysis
        $data = "=== DATA KEGIATAN UNTUK ANALISIS RISIKO ===\n";
        $data .= "Tanggal Analisis: " . now()->format('d F Y') . "\n\n";

        foreach ($kegiatans as $k) {
            $desaNama = $k->desa->nama ?? 'N/A';
            $kecNama = $k->desa->kecamatan->nama ?? 'N/A';
            $deadline = $k->tanggal_selesai ? $k->tanggal_selesai->format('Y-m-d') : 'Belum ditentukan';
            $mulai = $k->tanggal_mulai ? $k->tanggal_mulai->format('Y-m-d') : 'Belum ditentukan';
            $serapan = $k->pagu_anggaran > 0 ? round(($k->realisasi_anggaran / $k->pagu_anggaran) * 100, 1) : 0;
            $sisaHari = $k->tanggal_selesai ? now()->diffInDays($k->tanggal_selesai, false) : 'N/A';

            $data .= "Kegiatan: {$k->nama_kegiatan}\n";
            $data .= "  Desa: {$desaNama} | Kec: {$kecNama}\n";
            $data .= "  Progres Fisik: {$k->progres_fisik}% | Serapan Anggaran: {$serapan}%\n";
            $data .= "  Pagu: Rp " . number_format($k->pagu_anggaran, 0, ',', '.') . "\n";
            $data .= "  Mulai: {$mulai} | Deadline: {$deadline} | Sisa: {$sisaHari} hari\n";
            $data .= "  Status: {$k->status}\n\n";
        }

        $data .= "\n=== DATA DESA ===\n";
        foreach ($desas as $d) {
            $kecNama = $d->kecamatan->nama ?? 'N/A';
            $data .= "Desa: {$d->nama} | Kec: {$kecNama} | Jumlah Kegiatan: {$d->kegiatans_count}\n";
        }

        $systemPrompt = "Anda adalah AI Analis Risiko senior yang ahli dalam manajemen proyek pembangunan pemerintahan daerah. Tugas Anda adalah menganalisis risiko, memberikan skor, menentukan prioritas monitoring, dan memprediksi keterlambatan berdasarkan data aktual. Gunakan Bahasa Indonesia formal.";

        $userPrompt = $data . "\n\nBerdasarkan data di atas, buatlah analisis komprehensif dalam format Markdown:\n\n" .
            "## 🔴 Risk Scoring (Skor Risiko Kegiatan)\n" .
            "Berikan skor risiko 1-100 untuk setiap kegiatan. Gunakan tabel Markdown dengan kolom: Kegiatan | Desa | Skor Risiko | Level (Rendah/Sedang/Tinggi/Kritis) | Alasan.\n" .
            "Kriteria: progres rendah + deadline dekat = risiko tinggi; serapan anggaran rendah = risiko sedang.\n\n" .
            "## 🎯 Priority Monitoring\n" .
            "Tentukan 5 kegiatan dan desa yang harus diprioritaskan untuk monitoring mendesak oleh Camat. Jelaskan alasannya.\n\n" .
            "## 📈 Predictive Analytics\n" .
            "Prediksi kegiatan mana yang kemungkinan besar akan terlambat. Sertakan estimasi waktu keterlambatan dan saran mitigasi.\n\n" .
            "## 🏆 Performance Ranking\n" .
            "Buat ranking desa berdasarkan kinerja pembangunan (gunakan tabel Markdown). Kolom: Ranking | Desa | Kecamatan | Skor Kinerja | Catatan.\n\n" .
            "Gunakan emoji, tabel, dan format profesional.";

        $result = $this->callOpenAI($systemPrompt, $userPrompt, 2500);

        if (isset($result['error'])) {
            $this->riskError = $result['error'];
        } else {
            $this->riskResult = $result['content'];
        }

        $this->riskLoading = false;
    }

    public function render()
    {
        return view('livewire.ai-risk-monitor');
    }
}
