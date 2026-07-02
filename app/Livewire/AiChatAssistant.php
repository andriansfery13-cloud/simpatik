<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatAssistant extends Component
{
    public array $messages = [];
    public string $userMessage = '';
    public bool $chatLoading = false;
    public string $chatError = '';

    public function mount()
    {
        // Welcome message
        $this->messages[] = [
            'role' => 'assistant',
            'content' => "Selamat datang di **AI Assistant SIMPATIK** 🤖\n\nSaya siap membantu Anda menganalisis data pembangunan daerah. Anda bisa bertanya tentang:\n- 📊 Progres kegiatan dan anggaran\n- 🏘 Kinerja desa atau kecamatan tertentu\n- ⚠️ Kegiatan yang berisiko terlambat\n- 💡 Saran dan rekomendasi\n\nSilakan ketik pertanyaan Anda!",
        ];
    }

    protected function buildSystemContext(): string
    {
        $kegiatans = Kegiatan::with('desa.kecamatan')->get();
        $totalDesa = Desa::count();
        $totalKecamatan = Kecamatan::count();
        $totalKegiatan = $kegiatans->count();
        $totalAnggaran = $kegiatans->sum('pagu_anggaran');
        $totalRealisasi = $kegiatans->sum('realisasi_anggaran');
        $avgProgres = $kegiatans->avg('progres_fisik') ?? 0;
        $statusCounts = $kegiatans->groupBy('status')->map->count();

        $context = "Anda adalah Asisten AI SIMPATIK (Sistem Monitoring Pembangunan Terintegrasi Kecamatan) Kabupaten Bandung. ";
        $context .= "Anda membantu Camat, Bupati, dan pejabat pemerintah mendapatkan informasi secara cepat. ";
        $context .= "Jawab dengan singkat, jelas, dan profesional dalam Bahasa Indonesia. Gunakan format Markdown.\n\n";
        $context .= "=== DATA TERKINI ===\n";
        $context .= "Total Kecamatan: {$totalKecamatan}\n";
        $context .= "Total Desa: {$totalDesa}\n";
        $context .= "Total Kegiatan: {$totalKegiatan}\n";
        $context .= "Total Pagu Anggaran: Rp " . number_format($totalAnggaran, 0, ',', '.') . "\n";
        $context .= "Total Realisasi: Rp " . number_format($totalRealisasi, 0, ',', '.') . "\n";
        $context .= "Serapan: " . ($totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 1) : 0) . "%\n";
        $context .= "Rata-rata Progres Fisik: " . round($avgProgres, 1) . "%\n\n";

        $context .= "Status:\n";
        foreach ($statusCounts as $status => $count) {
            $context .= "- {$status}: {$count}\n";
        }

        $context .= "\nDetail Kegiatan:\n";
        foreach ($kegiatans->take(25) as $k) {
            $desaNama = $k->desa->nama ?? 'N/A';
            $kecNama = $k->desa->kecamatan->nama ?? 'N/A';
            $serapan = $k->pagu_anggaran > 0 ? round(($k->realisasi_anggaran / $k->pagu_anggaran) * 100, 1) : 0;
            $context .= "• {$k->nama_kegiatan} | Desa {$desaNama}, Kec. {$kecNama} | Progres: {$k->progres_fisik}% | Serapan: {$serapan}% | Status: {$k->status}\n";
        }

        return $context;
    }

    public function sendMessage()
    {
        if (empty(trim($this->userMessage))) return;

        $this->chatLoading = true;
        $this->chatError = '';

        // Add user message to chat
        $this->messages[] = [
            'role' => 'user',
            'content' => $this->userMessage,
        ];

        $userMsg = $this->userMessage;
        $this->userMessage = '';

        $apiKey = Setting::get('openai_api_key');

        if (!$apiKey) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => '⚠️ API Key OpenAI belum diatur. Silakan minta Administrator untuk mengaturnya di menu **Pengaturan Sistem**.',
            ];
            $this->chatLoading = false;
            return;
        }

        // Build conversation history for context (keep last 10 messages)
        $systemContext = $this->buildSystemContext();
        $apiMessages = [
            ['role' => 'system', 'content' => $systemContext],
        ];

        // Add recent conversation history (skip welcome message, take last 10)
        $recentMessages = array_slice($this->messages, max(0, count($this->messages) - 10));
        foreach ($recentMessages as $msg) {
            $apiMessages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => $apiMessages,
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => $content,
                ];
            } else {
                $errorMsg = $response->json('error.message') ?? 'Terjadi kesalahan.';
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => "⚠️ Gagal mendapatkan jawaban: {$errorMsg}",
                ];
            }
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            $this->messages[] = [
                'role' => 'assistant',
                'content' => '⚠️ Terjadi kesalahan sistem. Pastikan koneksi internet Anda aktif.',
            ];
        }

        $this->chatLoading = false;
    }

    public function clearChat()
    {
        $this->messages = [];
        $this->mount();
    }

    public function render()
    {
        return view('livewire.ai-chat-assistant');
    }
}
