<?php

namespace App\Livewire;

use App\Models\Desa;
use App\Models\Kecamatan;
use Livewire\Component;

class DesaForm extends Component
{
    public ?Desa $desaModel = null;
    public bool $isEdit = false;

    // Fields
    public ?int $kecamatan_id = null;
    public string $kode = '';
    public string $nama = '';
    public string $kepala_desa = '';
    public string $alamat = '';
    public string $telepon = '';
    public $jumlah_penduduk = '';
    public $luas_wilayah = '';

    // Dropdown
    public $kecamatans = [];

    public function mount(?Desa $desa = null)
    {
        $user = auth()->user();

        // Get accessible kecamatans
        if ($user->isKabupaten()) {
            $this->kecamatans = Kecamatan::orderBy('nama')->get();
        } elseif ($user->isKecamatan()) {
            $this->kecamatan_id = $user->kecamatan_id;
            $this->kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
        }

        if ($desa && $desa->exists) {
            $this->desaModel = $desa;
            $this->isEdit = true;
            $this->kecamatan_id = $desa->kecamatan_id;
            $this->kode = $desa->kode;
            $this->nama = $desa->nama;
            $this->kepala_desa = $desa->kepala_desa ?? '';
            $this->alamat = $desa->alamat ?? '';
            $this->telepon = $desa->telepon ?? '';
            $this->jumlah_penduduk = $desa->jumlah_penduduk ?? '';
            $this->luas_wilayah = $desa->luas_wilayah ?? '';
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    protected function rules(): array
    {
        $rules = [
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
            'kepala_desa' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'jumlah_penduduk' => 'nullable|integer|min:0',
            'luas_wilayah' => 'nullable|numeric|min:0',
        ];

        if ($this->isEdit) {
            $rules['kode'] = 'required|string|max:20|unique:desas,kode,' . $this->desaModel->id;
        } else {
            $rules['kode'] = 'required|string|max:20|unique:desas,kode';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            'kode.required' => 'Kode desa wajib diisi.',
            'kode.unique' => 'Kode desa sudah digunakan.',
            'nama.required' => 'Nama desa wajib diisi.',
        ];
    }

    public function save()
    {
        $validated = $this->validate($this->rules(), $this->messages());

        $data = [
            'kecamatan_id' => $this->kecamatan_id,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'kepala_desa' => $this->kepala_desa ?: null,
            'alamat' => $this->alamat ?: null,
            'telepon' => $this->telepon ?: null,
            'jumlah_penduduk' => $this->jumlah_penduduk ?: null,
            'luas_wilayah' => $this->luas_wilayah ?: null,
        ];

        if ($this->isEdit) {
            $this->desaModel->update($data);
            session()->flash('success', 'Data desa berhasil diperbarui.');
        } else {
            Desa::create($data);
            session()->flash('success', 'Data desa berhasil ditambahkan.');
        }

        return redirect()->route('desa.index');
    }

    public function render()
    {
        return view('livewire.desa-form');
    }
}
