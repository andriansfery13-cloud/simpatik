<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Hanya Super Admin yang dapat mengakses data kecamatan.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Kecamatan::withCount('desas');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $kecamatans = $query->orderBy('nama')->paginate(15);
        return view('kecamatan.index', compact('kecamatans'));
    }

    public function create()
    {
        return view('kecamatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:kecamatans',
            'nama' => 'required|string|max:255',
            'camat' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        Kecamatan::create($validated);
        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil ditambahkan.');
    }

    public function show(Kecamatan $kecamatan)
    {
        $kecamatan->load('desas');
        return view('kecamatan.show', compact('kecamatan'));
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('kecamatan.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:kecamatans,kode,' . $kecamatan->id,
            'nama' => 'required|string|max:255',
            'camat' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        $kecamatan->update($validated);
        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil dihapus.');
    }
}
