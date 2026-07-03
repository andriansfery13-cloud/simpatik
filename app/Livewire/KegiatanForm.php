<?php

namespace App\Livewire;

use App\Models\Kegiatan;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\SumberDana;
use Livewire\Component;

class KegiatanForm extends Component
{
    public ?Kegiatan $kegiatanModel = null;
    public bool $isEdit = false;

    // Fields
    public string $nama_kegiatan = '';
    public ?int $kecamatan_id = null;
    public ?int $desa_id = null;
    public ?int $sumber_dana_id = null;
    public string $deskripsi = '';
    public string $periode_anggaran = '';
    public string $lokasi = '';
    public ?float $latitude = null;
    public ?float $longitude = null;
    public $pagu_anggaran = '';
    public $realisasi_anggaran = '';
    public $progres_fisik = '';
    public string $tanggal_mulai = '';
    public string $tanggal_selesai = '';
    public string $pelaksana = '';
    public string $penanggung_jawab = '';
    public string $catatan = '';
    public string $status = 'belum_mulai';

    // Dropdown data
    public $kecamatans = [];
    public $desas = [];
    public $sumberDanas = [];

    public function mount(?Kegiatan $kegiatan = null)
    {
        $user = auth()->user();
        $this->sumberDanas = SumberDana::where('is_active', true)->get();

        if ($user->isKabupaten()) {
            $this->kecamatans = Kecamatan::orderBy('nama')->get();
        } elseif ($user->isKecamatan()) {
            $this->kecamatan_id = $user->kecamatan_id;
            $this->desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama')->get();
        } else {
            // Desa user
            $this->kecamatan_id = $user->kecamatan_id;
            $this->desa_id = $user->desa_id;
            $this->desas = Desa::where('id', $user->desa_id)->get();
        }

        if ($kegiatan && $kegiatan->exists) {
            $this->kegiatanModel = $kegiatan;
            $this->isEdit = true;
            $this->nama_kegiatan = $kegiatan->nama_kegiatan;
            $this->desa_id = $kegiatan->desa_id;
            $this->sumber_dana_id = $kegiatan->sumber_dana_id;
            $this->periode_anggaran = $kegiatan->periode_anggaran ?? '';
            $this->deskripsi = $kegiatan->deskripsi ?? '';
            $this->lokasi = $kegiatan->lokasi ?? '';
            $this->latitude = $kegiatan->latitude;
            $this->longitude = $kegiatan->longitude;
            $this->pagu_anggaran = $kegiatan->pagu_anggaran;
            $this->realisasi_anggaran = $kegiatan->realisasi_anggaran ?? '';
            $this->progres_fisik = $kegiatan->progres_fisik ?? '';
            $this->tanggal_mulai = $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->format('Y-m-d') : '';
            $this->tanggal_selesai = $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->format('Y-m-d') : '';
            $this->pelaksana = $kegiatan->pelaksana ?? '';
            $this->penanggung_jawab = $kegiatan->penanggung_jawab ?? '';
            $this->catatan = $kegiatan->catatan ?? '';
            $this->status = $kegiatan->status;

            // Load kecamatan for the selected desa
            if ($kegiatan->desa) {
                $this->kecamatan_id = $kegiatan->desa->kecamatan_id;
                $this->desas = Desa::where('kecamatan_id', $this->kecamatan_id)->orderBy('nama')->get();
            }
        }
    }

    public function updatedKecamatanId($value)
    {
        $this->desa_id = null;
        $this->desas = $value
            ? Desa::where('kecamatan_id', $value)->orderBy('nama')->get()
            : [];
    }

    public function updatedPeriodeAnggaran($value)
    {
        $year = date('Y');
        
        if ($value === 'Tahap 1') {
            $this->tanggal_mulai = "$year-01-01";
            $this->tanggal_selesai = "$year-06-30";
        } elseif ($value === 'Tahap 2') {
            $this->tanggal_mulai = "$year-07-01";
            $this->tanggal_selesai = "$year-09-30";
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    protected function rules(): array
    {
        $rules = [
            'nama_kegiatan' => 'required|string|max:255',
            'desa_id' => 'required|exists:desas,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'periode_anggaran' => 'nullable|string|in:Tahap 1,Tahap 2',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'pagu_anggaran' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'pelaksana' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
        ];

        if ($this->isEdit) {
            $rules['realisasi_anggaran'] = 'nullable|numeric|min:0';
            $rules['progres_fisik'] = 'nullable|numeric|min:0|max:100';
            $rules['status'] = 'required|in:belum_mulai,berjalan,selesai,terlambat';
            $rules['catatan'] = 'nullable|string';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'desa_id.required' => 'Desa lokasi wajib dipilih.',
            'sumber_dana_id.required' => 'Sumber dana wajib dipilih.',
            'pagu_anggaran.required' => 'Pagu anggaran wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ];
    }

    public function save()
    {
        $validated = $this->validate($this->rules(), $this->messages());

        $data = [
            'desa_id' => $this->desa_id,
            'sumber_dana_id' => $this->sumber_dana_id,
            'periode_anggaran' => $this->periode_anggaran ?: null,
            'nama_kegiatan' => $this->nama_kegiatan,
            'deskripsi' => $this->deskripsi ?: null,
            'lokasi' => $this->lokasi ?: null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'pagu_anggaran' => $this->pagu_anggaran,
            'tanggal_mulai' => $this->tanggal_mulai ?: null,
            'tanggal_selesai' => $this->tanggal_selesai ?: null,
            'pelaksana' => $this->pelaksana ?: null,
            'penanggung_jawab' => $this->penanggung_jawab ?: null,
        ];

        if ($this->isEdit) {
            $data['realisasi_anggaran'] = $this->realisasi_anggaran ?: 0;
            $data['progres_fisik'] = $this->progres_fisik ?: 0;
            $data['status'] = $this->status;
            $data['catatan'] = $this->catatan ?: null;
            $this->kegiatanModel->update($data);
            session()->flash('success', 'Kegiatan berhasil diperbarui.');
        } else {
            $data['tahun_anggaran'] = now()->year;
            $data['status'] = 'belum_mulai';
            Kegiatan::create($data);
            session()->flash('success', 'Kegiatan berhasil ditambahkan.');
        }

        return redirect()->route('kegiatan.index');
    }

    public function render()
    {
        return view('livewire.kegiatan-form');
    }
}
