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
        Schema::table('desas', function (Blueprint $table) {
            $table->json('data_wilayah')->nullable();
            $table->json('data_aparatur')->nullable();
            $table->json('data_potensi')->nullable();
            $table->json('data_infrastruktur')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropColumn(['data_wilayah', 'data_aparatur', 'data_potensi', 'data_infrastruktur']);
        });
    }
};
