<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('sumber_dana_id')->constrained('sumber_danas')->cascadeOnDelete();
            $table->year('tahun_anggaran');
            $table->decimal('pagu', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->enum('status_earmark', ['earmarked', 'non-earmarked'])->default('non-earmarked');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
