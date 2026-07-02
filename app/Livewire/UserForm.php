<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserForm extends Component
{
    // Form state
    public ?User $userModel = null;
    public bool $isEdit = false;

    // Fields
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';
    public ?int $kecamatan_id = null;
    public ?int $desa_id = null;
    public string $nip = '';
    public string $jabatan = '';
    public string $telepon = '';
    public bool $is_active = true;

    // Dropdown data
    public $kecamatans = [];
    public $desas = [];
    public $availableRoles = [];

    public function mount(?User $user = null)
    {
        $authUser = auth()->user();

        // Set available roles based on the auth user's level
        if ($authUser->isKabupaten()) {
            $this->availableRoles = [
                'admin' => 'Administrator',
                'bupati' => 'Bupati',
                'sekda' => 'Sekretaris Daerah',
                'dpmd' => 'DPMD',
                'bappeda' => 'Bappeda',
                'inspektorat' => 'Inspektorat',
                'camat' => 'Camat',
                'sekcam' => 'Sekretaris Camat',
                'kasi_pmd' => 'Kasi PMD',
                'operator_kecamatan' => 'Operator Kecamatan',
                'kepala_desa' => 'Kepala Desa',
                'sekretaris_desa' => 'Sekretaris Desa',
                'operator_desa' => 'Operator Desa',
            ];
            $this->kecamatans = Kecamatan::orderBy('nama')->get();
        } else {
            $this->availableRoles = [
                'kepala_desa' => 'Kepala Desa',
                'sekretaris_desa' => 'Sekretaris Desa',
                'operator_desa' => 'Operator Desa',
            ];
            $this->kecamatan_id = $authUser->kecamatan_id;
            $this->desas = Desa::where('kecamatan_id', $authUser->kecamatan_id)->orderBy('nama')->get();
        }

        // If editing, populate fields
        if ($user && $user->exists) {
            $this->userModel = $user;
            $this->isEdit = true;
            $this->name = $user->name;
            $this->username = $user->username ?? '';
            $this->email = $user->email;
            $this->role = $user->role;
            $this->kecamatan_id = $user->kecamatan_id;
            $this->desa_id = $user->desa_id;
            $this->nip = $user->nip ?? '';
            $this->jabatan = $user->jabatan ?? '';
            $this->telepon = $user->telepon ?? '';
            $this->is_active = $user->is_active;

            // Load desas for the selected kecamatan
            if ($this->kecamatan_id) {
                $this->desas = Desa::where('kecamatan_id', $this->kecamatan_id)->orderBy('nama')->get();
            }
        }
    }

    /**
     * When kecamatan_id changes, reload the desa dropdown.
     */
    public function updatedKecamatanId($value)
    {
        $this->desa_id = null;
        $this->desas = $value
            ? Desa::where('kecamatan_id', $value)->orderBy('nama')->get()
            : [];
    }

    /**
     * When role changes, reset tenant fields if the role doesn't need them.
     */
    public function updatedRole($value)
    {
        $kecamatanRoles = ['camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan', 'kepala_desa', 'sekretaris_desa', 'operator_desa'];
        $desaRoles = ['kepala_desa', 'sekretaris_desa', 'operator_desa'];

        if (!in_array($value, $kecamatanRoles)) {
            $this->kecamatan_id = null;
            $this->desas = [];
        }
        if (!in_array($value, $desaRoles)) {
            $this->desa_id = null;
        }
    }

    /**
     * Real-time validation on field update.
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users')->ignore($this->userModel?->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($this->userModel?->id),
            ],
            'role' => 'required|string',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $kecamatanRoles = ['camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan', 'kepala_desa', 'sekretaris_desa', 'operator_desa'];
        $desaRoles = ['kepala_desa', 'sekretaris_desa', 'operator_desa'];

        if (in_array($this->role, $kecamatanRoles)) {
            $rules['kecamatan_id'] = 'required|exists:kecamatans,id';
        }
        if (in_array($this->role, $desaRoles)) {
            $rules['desa_id'] = 'required|exists:desas,id';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh pengguna lain.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Peran (role) wajib dipilih.',
            'kecamatan_id.required' => 'Kecamatan wajib dipilih untuk role ini.',
            'desa_id.required' => 'Desa wajib dipilih untuk role ini.',
        ];
    }

    public function save()
    {
        $authUser = auth()->user();

        // Authorization: Non-kabupaten admins can only create desa roles
        if (!$authUser->isKabupaten()) {
            $this->kecamatan_id = $authUser->kecamatan_id;
            if (!in_array($this->role, ['kepala_desa', 'sekretaris_desa', 'operator_desa'])) {
                abort(403, 'Unauthorized role selection.');
            }
        }

        $validated = $this->validate($this->rules(), $this->messages());

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'kecamatan_id' => $this->kecamatan_id,
            'desa_id' => $this->desa_id,
            'nip' => $this->nip ?: null,
            'jabatan' => $this->jabatan ?: null,
            'telepon' => $this->telepon ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            if (!empty($this->password)) {
                $data['password'] = $this->password;
            }
            $this->userModel->update($data);
            session()->flash('success', 'Data pengguna berhasil diperbarui.');
        } else {
            $data['password'] = $this->password;
            User::create($data);
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
        }

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
