<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('progres_fisik', 5, 2)->default(0);
            $table->decimal('progres_keuangan', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_update');
            $table->timestamps();
        });

        Schema::create('kegiatan_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipe', ['sebelum', 'proses', 'sesudah']);
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('taken_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_dokumens');
        Schema::dropIfExists('kegiatan_progres');
    }
};
