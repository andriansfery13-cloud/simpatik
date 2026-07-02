<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'admin',
                'bupati', 'sekda', 'dpmd', 'bappeda', 'inspektorat',
                'camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan',
                'kepala_desa', 'sekretaris_desa', 'operator_desa'
            ])->default('operator_desa')->after('email');
            $table->foreignId('kecamatan_id')->nullable()->after('role')->constrained('kecamatans')->nullOnDelete();
            $table->foreignId('desa_id')->nullable()->after('kecamatan_id')->constrained('desas')->nullOnDelete();
            $table->string('nip', 30)->nullable()->after('desa_id');
            $table->string('jabatan')->nullable()->after('nip');
            $table->string('telepon', 20)->nullable()->after('jabatan');
            $table->string('foto')->nullable()->after('telepon');
            $table->boolean('is_active')->default(true)->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['desa_id']);
            $table->dropColumn([
                'role', 'kecamatan_id', 'desa_id',
                'nip', 'jabatan', 'telepon', 'foto', 'is_active'
            ]);
        });
    }
};
