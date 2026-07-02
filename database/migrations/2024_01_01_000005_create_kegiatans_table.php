<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('sumber_dana_id')->constrained('sumber_danas');
            $table->string('nama_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('pagu_anggaran', 15, 2)->default(0);
            $table->decimal('realisasi_anggaran', 15, 2)->default(0);
            $table->decimal('progres_fisik', 5, 2)->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->date('tanggal_realisasi_selesai')->nullable();
            $table->enum('status', ['belum_mulai', 'berjalan', 'selesai', 'terlambat'])->default('belum_mulai');
            $table->string('pelaksana')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->year('tahun_anggaran');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
