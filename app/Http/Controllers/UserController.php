<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Exports\UserExport;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function export(Request $request)
    {
        $user = auth()->user();
        $kecamatanId = $user->isKabupaten() ? null : $user->kecamatan_id;
        return Excel::download(new UserExport($kecamatanId), 'data_pengguna_simpatik.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            Excel::import(new UserImport, $request->file('file_excel'));
            return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Template Data
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import User');
        $sheet->setCellValue('A1', 'nama_lengkap');
        $sheet->setCellValue('B1', 'username');
        $sheet->setCellValue('C1', 'email');
        $sheet->setCellValue('D1', 'password');
        $sheet->setCellValue('E1', 'role');
        $sheet->setCellValue('F1', 'id_kecamatan');
        $sheet->setCellValue('G1', 'id_desa');
        $sheet->setCellValue('H1', 'status_aktif');
        
        // Example data
        $sheet->setCellValue('A2', 'Andi Operator');
        $sheet->setCellValue('B2', 'andi.opr');
        $sheet->setCellValue('C2', 'andi@mail.com');
        $sheet->setCellValue('D2', 'Rahasia123!');
        $sheet->setCellValue('E2', 'operator_desa');
        $sheet->setCellValue('F2', '1');
        $sheet->setCellValue('G2', '1');
        $sheet->setCellValue('H2', 'Aktif');
        
        // Styling header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2']
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Sheet 2: Referensi ID Wilayah
        $user = auth()->user();
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Referensi ID Wilayah');
        
        $refSheet->setCellValue('A1', 'ID Kecamatan');
        $refSheet->setCellValue('B1', 'Nama Kecamatan');
        $refSheet->setCellValue('D1', 'ID Desa');
        $refSheet->setCellValue('E1', 'Nama Desa');
        $refSheet->setCellValue('F1', 'ID Kecamatan');
        
        $refSheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        
        // Populate Data Kecamatan & Desa berdasarkan role
        if ($user->isKabupaten()) {
            $kecamatans = Kecamatan::all();
            $desas = Desa::with('kecamatan')->get();
        } else {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->get();
        }

        $rowKec = 2;
        foreach ($kecamatans as $kec) {
            $refSheet->setCellValue('A' . $rowKec, $kec->id);
            $refSheet->setCellValue('B' . $rowKec, $kec->nama);
            $rowKec++;
        }

        $rowDesa = 2;
        foreach ($desas as $desa) {
            $refSheet->setCellValue('D' . $rowDesa, $desa->id);
            $refSheet->setCellValue('E' . $rowDesa, $desa->nama);
            $refSheet->setCellValue('F' . $rowDesa, $desa->kecamatan_id);
            $rowDesa++;
        }
        
        // Sheet 3: Referensi Role
        $roleSheet = $spreadsheet->createSheet();
        $roleSheet->setTitle('Referensi Role');
        $roleSheet->setCellValue('A1', 'Role Tersedia');
        $roleSheet->getStyle('A1')->applyFromArray($headerStyle);
        
        $availableRoles = $user->isKabupaten() 
            ? ['admin', 'bupati', 'sekda', 'dpmd', 'bappeda', 'inspektorat', 'camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan', 'kepala_desa', 'sekretaris_desa', 'operator_desa']
            : ['kepala_desa', 'sekretaris_desa', 'operator_desa'];
            
        $rowRole = 2;
        foreach ($availableRoles as $role) {
            $roleSheet->setCellValue('A' . $rowRole, $role);
            $rowRole++;
        }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Import_User.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);
        
        return Response::download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
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
