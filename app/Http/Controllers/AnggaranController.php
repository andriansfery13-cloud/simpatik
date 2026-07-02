<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Desa;
use App\Models\SumberDana;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnggaranExport;
use App\Imports\AnggaranImport;

class AnggaranController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Anggaran::with(['desa.kecamatan', 'sumberDana']);

        if ($user->isKecamatan()) {
            $desaIds = Desa::where('kecamatan_id', $user->kecamatan_id)->pluck('id');
            $query->whereIn('desa_id', $desaIds);
        } elseif ($user->isDesa()) {
            $query->where('desa_id', $user->desa_id);
        }

        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }
        if ($request->filled('sumber_dana_id')) {
            $query->where('sumber_dana_id', $request->sumber_dana_id);
        }

        $anggarans = $query->orderBy('created_at', 'desc')->paginate(15);
        $sumberDanas = SumberDana::where('is_active', true)->get();

        // Summary stats
        $totalPagu = $query->sum('pagu');
        $totalRealisasi = $query->sum('realisasi');

        return view('anggaran.index', compact('anggarans', 'sumberDanas', 'totalPagu', 'totalRealisasi'));
    }

    public function create()
    {
        $user = auth()->user();
        $desas = $this->getAccessibleDesas($user);
        $sumberDanas = SumberDana::where('is_active', true)->get();
        return view('anggaran.create', compact('desas', 'sumberDanas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'tahun_anggaran' => 'required|integer|min:2020|max:2030',
            'pagu' => 'required|numeric|min:0',
            'status_earmark' => 'required|in:earmarked,non-earmarked',
            'keterangan' => 'nullable|string',
        ]);

        Anggaran::create($validated);
        return redirect()->route('anggaran.index')->with('success', 'Data anggaran berhasil ditambahkan.');
    }

    public function show(Anggaran $anggaran)
    {
        $anggaran->load(['desa.kecamatan', 'sumberDana']);
        return view('anggaran.show', compact('anggaran'));
    }

    public function edit(Anggaran $anggaran)
    {
        $user = auth()->user();
        $desas = $this->getAccessibleDesas($user);
        $sumberDanas = SumberDana::where('is_active', true)->get();
        return view('anggaran.edit', compact('anggaran', 'desas', 'sumberDanas'));
    }

    public function update(Request $request, Anggaran $anggaran)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'tahun_anggaran' => 'required|integer|min:2020|max:2030',
            'pagu' => 'required|numeric|min:0',
            'realisasi' => 'nullable|numeric|min:0',
            'status_earmark' => 'required|in:earmarked,non-earmarked',
            'keterangan' => 'nullable|string',
        ]);

        $anggaran->update($validated);
        return redirect()->route('anggaran.index')->with('success', 'Data anggaran berhasil diperbarui.');
    }

    public function destroy(Anggaran $anggaran)
    {
        $anggaran->delete();
        return redirect()->route('anggaran.index')->with('success', 'Data anggaran berhasil dihapus.');
    }

    private function getAccessibleDesas($user)
    {
        if ($user->isKabupaten() || $user->isAdmin()) {
            return Desa::with('kecamatan')->get();
        } elseif ($user->isKecamatan()) {
            return Desa::where('kecamatan_id', $user->kecamatan_id)->get();
        }
        return Desa::where('id', $user->desa_id)->get();
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Anggaran::with(['desa.kecamatan', 'sumberDana']);

        if ($user->isKecamatan()) {
            $desaIds = Desa::where('kecamatan_id', $user->kecamatan_id)->pluck('id');
            $query->whereIn('desa_id', $desaIds);
        } elseif ($user->isDesa()) {
            $query->where('desa_id', $user->desa_id);
        }

        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }
        if ($request->filled('sumber_dana_id')) {
            $query->where('sumber_dana_id', $request->sumber_dana_id);
        }

        $anggarans = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new AnggaranExport($anggarans), 'data_anggaran_simpatik_' . date('Ymd_His') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new AnggaranImport, $request->file('file_excel'));
            return redirect()->route('anggaran.index')->with('success', 'Data anggaran berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('anggaran.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'ID Desa', 'Nama Desa', 'ID Sumber Dana', 'Sumber Dana',
            'Tahun Anggaran', 'Pagu (Rp)', 'Realisasi (Rp)',
            'Status Earmark', 'Keterangan',
        ];

        $data = [
            [
                '1', 'Desa Sukamaju', '1', 'Dana Desa 2024',
                '2024', '500000000', '0', 'earmarked', 'Alokasi DAK untuk infrastruktur',
            ]
        ];

        $export = new class($headers, $data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $headings;
            protected $data;
            public function __construct($headings, $data) {
                $this->headings = $headings;
                $this->data = $data;
            }
            public function array(): array {
                return $this->data;
            }
            public function headings(): array {
                return $this->headings;
            }
        };

        return Excel::download($export, 'template_import_anggaran.xlsx');
    }
}
