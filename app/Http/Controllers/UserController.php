<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isKabupaten()) {
            $users = User::with(['kecamatan', 'desa'])->latest()->paginate(15);
        } else {
            // Kecamatan admin can only see users in their kecamatan
            $users = User::with(['kecamatan', 'desa'])
                ->where('kecamatan_id', $user->kecamatan_id)
                ->where('id', '!=', $user->id) // optional: exclude themselves from management view
                ->latest()
                ->paginate(15);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        $kecamatans = [];
        $desas = [];
        $roles = [];

        if ($user->isKabupaten()) {
            $kecamatans = Kecamatan::all();
            $roles = ['admin', 'bupati', 'sekda', 'dpmd', 'bappeda', 'inspektorat', 'camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan', 'kepala_desa', 'sekretaris_desa', 'operator_desa'];
        } else {
            // Kecamatan can only create desa users in their kecamatan
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->get();
            $roles = ['kepala_desa', 'sekretaris_desa', 'operator_desa'];
        }

        return view('users.create', compact('kecamatans', 'desas', 'roles', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authUser = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
        ];

        // Authorization checks
        if (!$authUser->isKabupaten()) {
            // Force kecamatan_id for non-kabupaten admins
            $request->merge(['kecamatan_id' => $authUser->kecamatan_id]);
            $rules['desa_id'] = 'required|exists:desas,id';
            
            // Ensure they are only creating desa roles
            if (!in_array($request->role, ['kepala_desa', 'sekretaris_desa', 'operator_desa'])) {
                abort(403, 'Unauthorized role selection.');
            }
        } else {
            $rules['kecamatan_id'] = 'nullable|exists:kecamatans,id';
            $rules['desa_id'] = 'nullable|exists:desas,id';
        }

        $validated = $request->validate($rules);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $authUser = auth()->user();

        // Authorization
        if (!$authUser->isKabupaten() && $user->kecamatan_id !== $authUser->kecamatan_id) {
            abort(403, 'Unauthorized access.');
        }

        $kecamatans = [];
        $desas = [];
        $roles = [];

        if ($authUser->isKabupaten()) {
            $kecamatans = Kecamatan::all();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->get();
            $roles = ['admin', 'bupati', 'sekda', 'dpmd', 'bappeda', 'inspektorat', 'camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan', 'kepala_desa', 'sekretaris_desa', 'operator_desa'];
        } else {
            $desas = Desa::where('kecamatan_id', $authUser->kecamatan_id)->get();
            $roles = ['kepala_desa', 'sekretaris_desa', 'operator_desa'];
        }

        return view('users.edit', compact('user', 'kecamatans', 'desas', 'roles', 'authUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        // Authorization
        if (!$authUser->isKabupaten() && $user->kecamatan_id !== $authUser->kecamatan_id) {
            abort(403, 'Unauthorized access.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        if (!$authUser->isKabupaten()) {
            $request->merge(['kecamatan_id' => $authUser->kecamatan_id]);
            $rules['desa_id'] = 'required|exists:desas,id';
        } else {
            $rules['kecamatan_id'] = 'nullable|exists:kecamatans,id';
            $rules['desa_id'] = 'nullable|exists:desas,id';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $authUser = auth()->user();

        // Authorization
        if (!$authUser->isKabupaten() && $user->kecamatan_id !== $authUser->kecamatan_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->id === $authUser->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
