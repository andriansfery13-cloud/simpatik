<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->date('tanggal_monitoring');
            $table->text('kondisi_lapangan')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('progres_saat_monitoring', 5, 2)->default(0);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        Schema::create('temuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_id')->constrained('monitorings')->cascadeOnDelete();
            $table->text('deskripsi_temuan');
            $table->enum('tingkat', ['rendah', 'sedang', 'tinggi', 'kritis']);
            $table->text('rekomendasi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->date('batas_waktu_tindak_lanjut')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temuans');
        Schema::dropIfExists('monitorings');
    }
};
