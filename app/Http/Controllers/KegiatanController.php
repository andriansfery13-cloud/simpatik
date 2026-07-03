<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\SumberDana;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KegiatanExport;
use App\Imports\KegiatanImport;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Kegiatan::with(['desa', 'sumberDana']);

        // Tenant filtering
        if ($user->isKecamatan()) {
            $desaIds = Desa::where('kecamatan_id', $user->kecamatan_id)->pluck('id');
            $query->whereIn('desa_id', $desaIds);
        } elseif ($user->isDesa()) {
            $query->where('desa_id', $user->desa_id);
        }

        // Search & filter
        if ($request->filled('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }

        $kegiatans = $query->orderBy('created_at', 'desc')->paginate(15);
        $desas = $this->getAccessibleDesas($user);
        $sumberDanas = SumberDana::where('is_active', true)->get();

        return view('kegiatan.index', compact('kegiatans', 'desas', 'sumberDanas'));
    }

    public function create()
    {
        $user = auth()->user();
        $desas = $this->getAccessibleDesas($user);
        $sumberDanas = SumberDana::where('is_active', true)->get();
        return view('kegiatan.create', compact('desas', 'sumberDanas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'pagu_anggaran' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'pelaksana' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
        ]);

        $validated['tahun_anggaran'] = now()->year;
        $validated['status'] = 'belum_mulai';

        Kegiatan::create($validated);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $this->authorizeTenantAccess($kegiatan);
        $kegiatan->load(['desa.kecamatan', 'sumberDana', 'progresUpdates.user', 'dokumens', 'monitorings.temuans']);
        return view('kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        $this->authorizeTenantAccess($kegiatan);
        $user = auth()->user();
        $desas = $this->getAccessibleDesas($user);
        $sumberDanas = SumberDana::where('is_active', true)->get();
        return view('kegiatan.edit', compact('kegiatan', 'desas', 'sumberDanas'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $this->authorizeTenantAccess($kegiatan);
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'pagu_anggaran' => 'required|numeric|min:0',
            'realisasi_anggaran' => 'nullable|numeric|min:0',
            'progres_fisik' => 'nullable|numeric|min:0|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'status' => 'required|in:belum_mulai,berjalan,selesai,terlambat',
            'pelaksana' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $kegiatan->update($validated);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $this->authorizeTenantAccess($kegiatan);
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
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
        $query = Kegiatan::with(['desa.kecamatan', 'sumberDana']);

        if ($user->isKecamatan()) {
            $desaIds = Desa::where('kecamatan_id', $user->kecamatan_id)->pluck('id');
            $query->whereIn('desa_id', $desaIds);
        } elseif ($user->isDesa()) {
            $query->where('desa_id', $user->desa_id);
        }

        if ($request->filled('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }

        $kegiatans = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new KegiatanExport($kegiatans), 'data_kegiatan_simpatik_' . date('Ymd_His') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new KegiatanImport(auth()->user()), $request->file('file_excel'));
            return redirect()->route('kegiatan.index')->with('success', 'Data kegiatan berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('kegiatan.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'ID Desa', 'Nama Desa', 'ID Sumber Dana', 'Sumber Dana',
            'Nama Kegiatan', 'Deskripsi', 'Lokasi', 'Pagu Anggaran',
            'Realisasi Anggaran', 'Progres Fisik', 'Tanggal Mulai',
            'Tanggal Selesai', 'Status', 'Pelaksana', 'Penanggung Jawab',
            'Tahun Anggaran', 'Periode Anggaran',
        ];

        $user = auth()->user();
        $idDesa = '1';
        $namaDesa = 'Desa Sukamaju';

        if ($user && $user->isDesa() && $user->desa) {
            $idDesa = (string) $user->desa_id;
            $namaDesa = $user->desa->nama;
        } elseif ($user && $user->isKecamatan()) {
            $desa = \App\Models\Desa::where('kecamatan_id', $user->kecamatan_id)->first();
            if ($desa) {
                $idDesa = (string) $desa->id;
                $namaDesa = $desa->nama;
            }
        }

        $data = [
            [
                $idDesa, $namaDesa, '1', 'Dana Desa 2024',
                'Pembangunan Jalan Desa', 'Pengecoran jalan lingkungan RT 03',
                'RT 03 RW 01', '150000000', '0', '0',
                '2024-03-01', '2024-06-30', 'Belum Mulai',
                'CV Karya Mandiri', 'H. Ahmad', date('Y'), 'Semester 1',
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

        return Excel::download($export, 'template_import_kegiatan.xlsx');
    }

    private function authorizeTenantAccess(Kegiatan $kegiatan)
    {
        $user = auth()->user();
        $desa = $kegiatan->desa;

        if ($user->isKecamatan() && $desa->kecamatan_id !== $user->kecamatan_id) {
            abort(403, 'Unauthorized. Anda hanya dapat mengakses kegiatan di wilayah kecamatan Anda.');
        }
        if ($user->isDesa() && $kegiatan->desa_id !== $user->desa_id) {
            abort(403, 'Unauthorized. Anda hanya dapat mengakses kegiatan di desa Anda sendiri.');
        }
    }
}
