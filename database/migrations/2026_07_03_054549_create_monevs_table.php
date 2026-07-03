<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monevs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            $table->date('tanggal_monev');
            
            // Data JSON untuk checklist (agar fleksibel jika ada penambahan aspek tanpa ubah skema DB)
            $table->json('aspek_perencanaan')->nullable();
            $table->json('aspek_keuangan')->nullable();
            $table->json('aspek_pelaksanaan')->nullable();
            $table->json('aspek_fisik')->nullable();
            $table->json('aspek_pelaporan')->nullable();

            // Skor otomatis (skala 0-100)
            $table->decimal('skor_perencanaan', 5, 2)->default(0);
            $table->decimal('skor_keuangan', 5, 2)->default(0);
            $table->decimal('skor_pelaksanaan', 5, 2)->default(0);
            $table->decimal('skor_fisik', 5, 2)->default(0);
            $table->decimal('skor_pelaporan', 5, 2)->default(0);
            $table->decimal('skor_total', 5, 2)->default(0);

            // AI Features
            $table->text('ai_insight')->nullable();
            $table->text('ai_temuan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monevs');
    }
};
