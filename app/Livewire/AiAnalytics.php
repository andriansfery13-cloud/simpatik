<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Kegiatan;
use App\Models\Desa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAnalytics extends Component
{
    public string $activeTab = 'executive';

    // Tab 1: Executive Dashboard
    public string $executiveResult = '';
    public bool $executiveLoading = false;
    public string $executiveError = '';

    public function switchTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Helper: Call OpenAI API
     */
    protected function callOpenAI(string $systemPrompt, string $userPrompt, int $maxTokens = 1200): array
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
                'temperature' => 0.7,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->successful()) {
                return ['content' => $response->json('choices.0.message.content')];
            } else {
                $errorMsg = $response->json('error.message') ?? 'Terjadi kesalahan API.';
                return ['error' => 'Gagal: ' . $errorMsg];
            }
        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            return ['error' => 'Kesalahan sistem: pastikan koneksi internet aktif. (' . $e->getMessage() . ')'];
        }
    }

    /**
     * Helper: Build comprehensive data summary for AI prompts
     */
    protected function buildDataSummary(): string
    {
        $kegiatans = Kegiatan::with('desa.kecamatan')->get();
        $desas = Desa::with('kecamatan')->get();

        $totalKegiatan = $kegiatans->count();
        $totalAnggaran = $kegiatans->sum('pagu_anggaran');
        $totalRealisasi = $kegiatans->sum('realisasi_anggaran');
        $avgProgres = $kegiatans->avg('progres_fisik') ?? 0;
        $statusCounts = $kegiatans->groupBy('status')->map->count();
        $totalDesa = $desas->count();

        $summary = "=== DATA PEMBANGUNAN SIMPATIK ===\n";
        $summary .= "Total Desa: {$totalDesa}\n";
        $summary .= "Total Kegiatan: {$totalKegiatan}\n";
        $summary .= "Total Pagu Anggaran: Rp " . number_format($totalAnggaran, 0, ',', '.') . "\n";
        $summary .= "Total Realisasi Anggaran: Rp " . number_format($totalRealisasi, 0, ',', '.') . "\n";
        $summary .= "Rata-rata Progres Fisik: " . round($avgProgres, 1) . "%\n";
        $summary .= "Serapan Anggaran: " . ($totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 1) : 0) . "%\n\n";

        $summary .= "Status Kegiatan:\n";
        foreach ($statusCounts as $status => $count) {
            $summary .= "- {$status}: {$count}\n";
        }

        // Detail per kegiatan (max 30 untuk konteks)
        $summary .= "\n=== DETAIL KEGIATAN ===\n";
        foreach ($kegiatans->take(30) as $k) {
            $desaNama = $k->desa->nama ?? 'N/A';
            $kecNama = $k->desa->kecamatan->nama ?? 'N/A';
            $deadline = $k->tanggal_selesai ? $k->tanggal_selesai->format('Y-m-d') : 'Belum ditentukan';
            $isLate = $k->is_late ? ' [TERLAMBAT]' : '';

            $summary .= "• {$k->nama_kegiatan} | Desa: {$desaNama} | Kec: {$kecNama} | ";
            $summary .= "Pagu: Rp " . number_format($k->pagu_anggaran, 0, ',', '.') . " | ";
            $summary .= "Realisasi: Rp " . number_format($k->realisasi_anggaran, 0, ',', '.') . " | ";
            $summary .= "Progres: {$k->progres_fisik}% | Status: {$k->status} | Deadline: {$deadline}{$isLate}\n";
        }

        return $summary;
    }

    /**
     * Tab 1: AI Executive Insight + Recommendation + Budget Analytics
     */
    public function analyzeExecutive()
    {
        $this->executiveLoading = true;
        $this->executiveError = '';
        $this->executiveResult = '';

        $dataSummary = $this->buildDataSummary();

        $systemPrompt = "Anda adalah AI Analis Senior Pembangunan Pemerintahan Daerah. Anda menganalisis data dari aplikasi SIMPATIK (Sistem Monitoring Pembangunan Terintegrasi Kecamatan) Kabupaten Bandung. Berikan analisis dalam Bahasa Indonesia formal yang mudah dipahami oleh Bupati dan Camat.";

        $userPrompt = $dataSummary . "\n\nBerdasarkan data di atas, buatlah laporan analisis komprehensif dalam format Markdown dengan struktur berikut:\n\n" .
            "## 📊 Ringkasan Eksekutif\nGambaran umum kondisi pembangunan saat ini.\n\n" .
            "## 💰 Analisis Anggaran\nAnalisis serapan anggaran, efisiensi, dan kepatuhan. Identifikasi kegiatan yang serapannya di bawah rata-rata.\n\n" .
            "## 🎯 Rekomendasi Strategis\nBerikan 5 rekomendasi konkret yang bisa diambil oleh pimpinan daerah.\n\n" .
            "## ⚠️ Peringatan Dini\nIdentifikasi kegiatan yang berisiko terlambat atau memiliki masalah anggaran.\n\n" .
            "Gunakan emoji yang tepat, tabel jika perlu, dan format yang profesional.";

        $result = $this->callOpenAI($systemPrompt, $userPrompt, 2000);

        if (isset($result['error'])) {
            $this->executiveError = $result['error'];
        } else {
            $this->executiveResult = $result['content'];
        }

        $this->executiveLoading = false;
    }

    public function render()
    {
        return view('livewire.ai-analytics');
    }
}
