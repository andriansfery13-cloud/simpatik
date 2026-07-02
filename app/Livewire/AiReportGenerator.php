<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiReportGenerator extends Component
{
    public string $reportResult = '';
    public bool $reportLoading = false;
    public string $reportError = '';
    public string $reportType = 'bulanan';
    public string $reportPeriod = '';

    public function mount()
    {
        $this->reportPeriod = now()->format('F Y');
    }

    protected function callOpenAI(string $systemPrompt, string $userPrompt, int $maxTokens = 3000): array
    {
        $apiKey = Setting::get('openai_api_key');

        if (!$apiKey) {
            return ['error' => 'API Key OpenAI belum diatur. Silakan atur di menu Pengaturan Sistem.'];
        }

        try {
            $response = Http::timeout(90)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 0.6,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->successful()) {
                return ['content' => $response->json('choices.0.message.content')];
            } else {
                $errorMsg = $response->json('error.message') ?? 'Terjadi kesalahan API.';
                return ['error' => 'Gagal: ' . $errorMsg];
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Report Error: ' . $e->getMessage());
            return ['error' => 'Kesalahan sistem: ' . $e->getMessage()];
        }
    }

    public function generateReport()
    {
        $this->reportLoading = true;
        $this->reportError = '';
        $this->reportResult = '';

        $kegiatans = Kegiatan::with('desa.kecamatan')->get();
        $totalKegiatan = $kegiatans->count();
        $totalAnggaran = $kegiatans->sum('pagu_anggaran');
        $totalRealisasi = $kegiatans->sum('realisasi_anggaran');
        $avgProgres = $kegiatans->avg('progres_fisik') ?? 0;
        $statusCounts = $kegiatans->groupBy('status')->map->count();
        $totalDesa = Desa::count();
        $totalKecamatan = Kecamatan::count();

        $data = "=== DATA SIMPATIK KABUPATEN BANDUNG ===\n";
        $data .= "Periode Laporan: {$this->reportPeriod}\n";
        $data .= "Jenis Laporan: Laporan " . ucfirst($this->reportType) . "\n";
        $data .= "Total Kecamatan: {$totalKecamatan}\n";
        $data .= "Total Desa: {$totalDesa}\n";
        $data .= "Total Kegiatan: {$totalKegiatan}\n";
        $data .= "Total Pagu Anggaran: Rp " . number_format($totalAnggaran, 0, ',', '.') . "\n";
        $data .= "Total Realisasi: Rp " . number_format($totalRealisasi, 0, ',', '.') . "\n";
        $data .= "Serapan Anggaran: " . ($totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 1) : 0) . "%\n";
        $data .= "Rata-rata Progres Fisik: " . round($avgProgres, 1) . "%\n\n";

        $data .= "Status Kegiatan:\n";
        foreach ($statusCounts as $status => $count) {
            $data .= "- {$status}: {$count}\n";
        }

        // Per-kegiatan detail
        $data .= "\nDetail Kegiatan:\n";
        foreach ($kegiatans->take(30) as $k) {
            $desaNama = $k->desa->nama ?? 'N/A';
            $kecNama = $k->desa->kecamatan->nama ?? 'N/A';
            $serapan = $k->pagu_anggaran > 0 ? round(($k->realisasi_anggaran / $k->pagu_anggaran) * 100, 1) : 0;

            $data .= "• {$k->nama_kegiatan} | Desa {$desaNama}, Kec. {$kecNama} | ";
            $data .= "Pagu: Rp " . number_format($k->pagu_anggaran, 0, ',', '.') . " | ";
            $data .= "Progres: {$k->progres_fisik}% | Serapan: {$serapan}% | Status: {$k->status}\n";
        }

        $systemPrompt = "Anda adalah AI Penyusun Laporan Resmi Pemerintahan. Anda menyusun laporan formal untuk Bupati/Camat yang profesional, terstruktur, dan berisi analisis data. Gunakan Bahasa Indonesia formal khas pemerintahan.";

        $typeLabel = ucfirst($this->reportType);
        $userPrompt = $data . "\n\nSusun Laporan {$typeLabel} Pembangunan Kabupaten Bandung periode {$this->reportPeriod} dalam format Markdown resmi pemerintahan dengan struktur:\n\n" .
            "# 📋 LAPORAN {$typeLabel} PEMBANGUNAN\n" .
            "## KABUPATEN BANDUNG - Periode {$this->reportPeriod}\n\n" .
            "### I. Pendahuluan\nLatar belakang dan tujuan laporan.\n\n" .
            "### II. Ringkasan Data Pembangunan\nSajikan data utama dalam tabel Markdown yang rapi.\n\n" .
            "### III. Analisis Capaian Kinerja\nAnalisis progres fisik dan keuangan. Bandingkan target vs realisasi.\n\n" .
            "### IV. Identifikasi Permasalahan\nDaftar kegiatan bermasalah (terlambat, serapan rendah, dll).\n\n" .
            "### V. Rekomendasi Tindak Lanjut\nRekomendasi konkret dan terukur untuk perbaikan.\n\n" .
            "### VI. Penutup\nKesimpulan umum dan harapan ke depan.\n\n" .
            "Gunakan format formal, data akurat dari statistik yang diberikan, dan tabel Markdown.";

        $result = $this->callOpenAI($systemPrompt, $userPrompt, 3000);

        if (isset($result['error'])) {
            $this->reportError = $result['error'];
        } else {
            $this->reportResult = $result['content'];
        }

        $this->reportLoading = false;
    }

    public function render()
    {
        return view('livewire.ai-report-generator');
    }
}
