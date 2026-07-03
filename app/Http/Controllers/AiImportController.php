<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\SumberDana;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

class AiImportController extends Controller
{
    public function importKegiatan(Request $request)
    {
        // Jika user level desa, larang import (atau sesuaikan dengan kebutuhan)
        // Opsional: if (auth()->user()->isDesa()) abort(403);

        $request->validate([
            'file_pdf' => 'required|mimes:pdf|max:10240' // max 10MB
        ]);

        $apiKey = Setting::get('openai_api_key');
        if (!$apiKey) {
            return redirect()->back()->with('error', 'API Key OpenAI belum dikonfigurasi. Hubungi Admin.');
        }

        try {
            // 1. Ekstrak Teks dari PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($request->file('file_pdf')->getPathname());
            $text = $pdf->getText();

            if (empty(trim($text))) {
                return redirect()->back()->with('error', 'Gagal membaca isi PDF atau PDF berisi gambar hasil scan.');
            }

            // Batasi panjang teks jika terlalu besar agar tidak melebihi token limit
            $text = substr($text, 0, 12000);

            // 2. Kirim ke OpenAI
            $prompt = $this->buildPrompt($text);

            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah asisten data extraction. Jawab HANYA menggunakan format JSON valid. Jangan gunakan tag markdown seperti ```json.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.1,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Gagal terhubung ke OpenAI: ' . $response->json('error.message', 'Unknown Error'));
            }

            // 3. Proses Response AI
            $jsonString = $response->json('choices.0.message.content');
            // Bersihkan jika ada sisa markdown
            $jsonString = trim(str_replace(['```json', '```'], '', $jsonString));
            
            $dataKegiatan = json_decode($jsonString, true);
            
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($dataKegiatan)) {
                throw new \Exception('Format respons AI tidak valid. Data tidak dapat diproses.');
            }

            if (empty($dataKegiatan)) {
                return redirect()->back()->with('error', 'AI tidak menemukan data kegiatan pembangunan di dalam PDF ini.');
            }

            // 4. Simpan ke Database (dengan filter/lock ID sesuai role)
            $user = auth()->user();
            $count = 0;

            foreach ($dataKegiatan as $item) {
                // Lewati data yang tidak valid
                if (empty($item['nama_kegiatan'])) continue;

                $desaId = $this->resolveDesaId($item, $user);
                $sumberDanaId = $this->resolveSumberDanaId($item);

                $statusMap = [
                    'belum mulai' => 'belum_mulai',
                    'berjalan' => 'berjalan',
                    'selesai' => 'selesai',
                    'terlambat' => 'terlambat',
                ];
                $rawStatus = strtolower(trim($item['status'] ?? 'belum_mulai'));
                $status = $statusMap[$rawStatus] ?? 'belum_mulai';

                // Format tanggal ke Y-m-d
                $tglMulai = $this->formatDate($item['tanggal_mulai'] ?? null);
                $tglSelesai = $this->formatDate($item['tanggal_selesai'] ?? null);

                Kegiatan::create([
                    'desa_id' => $desaId,
                    'sumber_dana_id' => $sumberDanaId,
                    'nama_kegiatan' => $item['nama_kegiatan'],
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'lokasi' => $item['lokasi'] ?? null,
                    'pagu_anggaran' => is_numeric($item['pagu_anggaran']) ? $item['pagu_anggaran'] : 0,
                    'realisasi_anggaran' => is_numeric($item['realisasi_anggaran']) ? $item['realisasi_anggaran'] : 0,
                    'progres_fisik' => is_numeric($item['progres_fisik']) ? $item['progres_fisik'] : 0,
                    'tanggal_mulai' => $tglMulai,
                    'tanggal_selesai' => $tglSelesai,
                    'status' => $status,
                    'pelaksana' => $item['pelaksana'] ?? null,
                    'penanggung_jawab' => $item['penanggung_jawab'] ?? null,
                    'tahun_anggaran' => $item['tahun_anggaran'] ?? now()->year,
                    'periode_anggaran' => $item['periode_anggaran'] ?? null,
                ]);

                $count++;
            }

            return redirect()->back()->with('success', "Berhasil mengekstrak dan menyimpan {$count} data kegiatan via AI.");

        } catch (\Exception $e) {
            Log::error('AI PDF Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
        }
    }

    private function buildPrompt(string $text): string
    {
        return <<<PROMPT
Ekstrak daftar kegiatan pembangunan dari teks dokumen berikut.
Kembalikan hasilnya HANYA dalam format array of JSON objects.
Tiap object harus memiliki field (key) berikut:
- "nama_kegiatan" (string)
- "nama_desa" (string, nama desa jika disebutkan)
- "sumber_dana" (string, misalnya Dana Desa, APBD, dsb)
- "deskripsi" (string, penjelasan singkat)
- "lokasi" (string)
- "pagu_anggaran" (number, wajib berupa angka utuh, buang 'Rp' atau titik)
- "realisasi_anggaran" (number, angka utuh)
- "progres_fisik" (number, persentase tanpa %, max 100)
- "tanggal_mulai" (string, format YYYY-MM-DD atau kosongkan jika tidak ada)
- "tanggal_selesai" (string, format YYYY-MM-DD atau kosongkan jika tidak ada)
- "status" (string, pilih salah satu: 'belum_mulai', 'berjalan', 'selesai', 'terlambat')
- "pelaksana" (string)
- "penanggung_jawab" (string)
- "tahun_anggaran" (number, tahun misal 2024)
- "periode_anggaran" (string, misal Semester 1)

Jika data tidak lengkap, isi dengan null atau kosong string untuk field tersebut, KECUALI pagu_anggaran, realisasi, dan progres wajib 0 jika tidak tahu.
Hanya ekstrak data kegiatan/proyek pembangunan fisik.

Teks dokumen:
====================
{$text}
====================
PROMPT;
    }

    private function resolveDesaId($item, $user): int
    {
        if ($user && $user->isDesa()) {
            return $user->desa_id;
        }

        $desaId = null;
        if (!empty($item['nama_desa'])) {
            $query = Desa::where('nama', 'LIKE', '%' . trim($item['nama_desa']) . '%');
            if ($user && $user->isKecamatan()) {
                $query->where('kecamatan_id', $user->kecamatan_id);
            }
            $desa = $query->first();
            $desaId = $desa ? $desa->id : null;
        }

        if (!$desaId) {
            if ($user && $user->isKecamatan()) {
                $desaId = Desa::where('kecamatan_id', $user->kecamatan_id)->first()->id ?? 1;
            } else {
                $desaId = Desa::first()->id ?? 1;
            }
        }
        return $desaId;
    }

    private function resolveSumberDanaId($item): int
    {
        $sdId = null;
        if (!empty($item['sumber_dana'])) {
            $sd = SumberDana::where('nama', 'LIKE', '%' . trim($item['sumber_dana']) . '%')->first();
            $sdId = $sd ? $sd->id : null;
        }
        return $sdId ?? (SumberDana::first()->id ?? 1);
    }

    private function formatDate($dateString)
    {
        if (empty($dateString) || $dateString === 'null') return null;
        try {
            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
