<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan kolom wajib diisi
        if (empty($row['nama_lengkap']) || empty($row['username']) || empty($row['email']) || empty($row['role'])) {
            return null;
        }

        $user = auth()->user();
        
        $kecamatanId = $row['id_kecamatan'] ?? null;
        $desaId = $row['id_desa'] ?? null;
        
        // Pengecekan keamanan level user
        if ($user && !$user->isKabupaten()) {
            // Paksa id kecamatan mengikuti user yang login
            $kecamatanId = $user->kecamatan_id;
            
            // Validasi id desa, pastikan desa_id yang dimasukkan memang berada di bawah kecamatan user
            if (in_array($row['role'], ['kepala_desa', 'sekretaris_desa', 'operator_desa'])) {
                if ($desaId) {
                    $desa = Desa::where('id', $desaId)->where('kecamatan_id', $kecamatanId)->first();
                    if (!$desa) {
                        // Jika desa_id ngawur/bukan dari kecamatannya, set ke desa pertama di kecamatan itu
                        $desaId = Desa::where('kecamatan_id', $kecamatanId)->first()->id ?? null;
                    }
                } else {
                    $desaId = Desa::where('kecamatan_id', $kecamatanId)->first()->id ?? null;
                }
            } else {
                // Untuk user level kecamatan, desa_id null
                $desaId = null;
                
                // Pastikan user level kecamatan tidak bisa membuat admin kabupaten
                if (in_array($row['role'], ['admin', 'bupati', 'sekda', 'dpmd', 'bappeda', 'inspektorat'])) {
                    return null; // Skip baris ini
                }
            }
        }

        // Cek jika user sudah ada (update)
        $existingUser = User::where('username', $row['username'])->orWhere('email', $row['email'])->first();

        $isActive = isset($row['status_aktif']) ? (strtolower(trim($row['status_aktif'])) === 'aktif' ? 1 : 0) : 1;
        
        if ($existingUser) {
            $updateData = [
                'name' => $row['nama_lengkap'],
                'role' => $row['role'],
                'kecamatan_id' => $kecamatanId,
                'desa_id' => $desaId,
                'is_active' => $isActive,
            ];
            
            if (!empty($row['password'])) {
                $updateData['password'] = Hash::make($row['password']);
            }
            
            $existingUser->update($updateData);
            return null;
        }

        // Jika tidak ada password untuk user baru, beri default password
        $password = empty($row['password']) ? 'password123' : $row['password'];

        return new User([
            'name' => $row['nama_lengkap'],
            'username' => $row['username'],
            'email' => $row['email'],
            'password' => Hash::make($password),
            'role' => $row['role'],
            'kecamatan_id' => $kecamatanId,
            'desa_id' => $desaId,
            'is_active' => $isActive,
        ]);
    }
}
