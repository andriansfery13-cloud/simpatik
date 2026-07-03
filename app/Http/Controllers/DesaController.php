<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DesaExport;
use App\Imports\DesaImport;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Desa::with('kecamatan');

        if ($user->isKecamatan()) {
            $query->where('kecamatan_id', $user->kecamatan_id);
        } elseif ($user->isDesa()) {
            $query->where('id', $user->desa_id);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        $desas = $query->orderBy('nama')->paginate(15);
        $kecamatans = Kecamatan::orderBy('nama')->get();

        return view('desa.index', compact('desas', 'kecamatans'));
    }

    public function create()
    {
        if (auth()->user()->isDesa()) abort(403, 'Unauthorized');

        $kecamatans = Kecamatan::orderBy('nama')->get();
        return view('desa.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isDesa()) abort(403, 'Unauthorized');

        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kode' => 'required|string|max:20|unique:desas',
            'nama' => 'required|string|max:255',
            'kepala_desa' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'jumlah_penduduk' => 'nullable|integer|min:0',
            'luas_wilayah' => 'nullable|numeric|min:0',
        ]);

        Desa::create($validated);
        return redirect()->route('desa.index')->with('success', 'Data desa berhasil ditambahkan.');
    }

    public function show(Desa $desa)
    {
        $this->authorizeTenantAccess($desa);
        $desa->load(['kecamatan', 'kegiatans.sumberDana', 'anggarans.sumberDana']);
        return view('desa.show', compact('desa'));
    }

    public function edit(Desa $desa)
    {
        $this->authorizeTenantAccess($desa);
        $kecamatans = Kecamatan::orderBy('nama')->get();
        return view('desa.edit', compact('desa', 'kecamatans'));
    }

    public function update(Request $request, Desa $desa)
    {
        $this->authorizeTenantAccess($desa);
        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kode' => 'required|string|max:20|unique:desas,kode,' . $desa->id,
            'nama' => 'required|string|max:255',
            'kepala_desa' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'jumlah_penduduk' => 'nullable|integer|min:0',
            'luas_wilayah' => 'nullable|numeric|min:0',
        ]);

        $desa->update($validated);
        return redirect()->route('desa.index')->with('success', 'Data desa berhasil diperbarui.');
    }

    public function destroy(Desa $desa)
    {
        $this->authorizeTenantAccess($desa);
        $desa->delete();
        return redirect()->route('desa.index')->with('success', 'Data desa berhasil dihapus.');
    }

    private function authorizeTenantAccess(Desa $desa)
    {
        $user = auth()->user();
        if ($user->isKecamatan() && $desa->kecamatan_id !== $user->kecamatan_id) {
            abort(403, 'Unauthorized. Anda hanya dapat mengakses desa di wilayah kecamatan Anda.');
        }
        if ($user->isDesa() && $desa->id !== $user->desa_id) {
            abort(403, 'Unauthorized. Anda hanya dapat mengakses data desa Anda sendiri.');
        }
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Desa::with('kecamatan');

        if ($user->isKecamatan()) {
            $query->where('kecamatan_id', $user->kecamatan_id);
        } elseif ($user->isDesa()) {
            $query->where('id', $user->desa_id);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        $desas = $query->orderBy('nama')->get();

        return Excel::download(new DesaExport($desas), 'data_desa_simpatik_' . date('Ymd_His') . '.xlsx');
    }

    public function import(Request $request)
    {
        if (auth()->user()->isDesa()) abort(403, 'Unauthorized');

        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new DesaImport(auth()->user()), $request->file('file_excel'));
            return redirect()->route('desa.index')->with('success', 'Data desa berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('desa.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'ID Kecamatan',
            'Nama Kecamatan',
            'Kode Desa',
            'Nama Desa',
            'Kepala Desa',
            'Alamat',
            'Telepon',
            'Latitude',
            'Longitude',
            'Jumlah Penduduk',
            'Luas Wilayah (Ha)',
            'Status Aktif'
        ];

        $user = auth()->user();
        $idKecamatan = '1';
        $namaKecamatan = 'Soreang';

        if ($user && $user->isKecamatan() && $user->kecamatan) {
            $idKecamatan = (string) $user->kecamatan_id;
            $namaKecamatan = $user->kecamatan->nama;
        }

        // Create a dummy template array
        $data = [
            [
                $idKecamatan, $namaKecamatan, '32.04.14.2001', 'Desa Sukamaju', 'H. Ahmad', 'Jl Raya Sukamaju No 1', '081234567890', '-7.0342', '107.5255', '5000', '150', 'Aktif'
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

        return Excel::download($export, 'template_import_desa.xlsx');
    }
}
