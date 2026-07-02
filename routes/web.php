<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\TransparansiController;
use App\Http\Controllers\GisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DokumentasiController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Transparansi Publik (no auth)
Route::get('/publik', [TransparansiController::class, 'index'])->name('transparansi.index');
Route::get('/publik/{kegiatan}', [TransparansiController::class, 'show'])->name('transparansi.show');
Route::get('/api/desa-by-kecamatan/{kecamatan}', function (\App\Models\Kecamatan $kecamatan) {
    return $kecamatan->desas()->orderBy('nama')->get(['id', 'nama']);
})->name('api.desa-by-kecamatan');

// Authenticated routes
use App\Http\Controllers\UserController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);

    // Kecamatan
    Route::resource('kecamatan', KecamatanController::class);

    // Desa
    Route::get('desa/export', [DesaController::class, 'export'])->name('desa.export');
    Route::post('desa/import', [DesaController::class, 'import'])->name('desa.import');
    Route::get('desa/template', [DesaController::class, 'downloadTemplate'])->name('desa.template');
    Route::resource('desa', DesaController::class);

    // Kegiatan
    Route::get('kegiatan/export', [KegiatanController::class, 'export'])->name('kegiatan.export');
    Route::post('kegiatan/import', [KegiatanController::class, 'import'])->name('kegiatan.import');
    Route::get('kegiatan/template', [KegiatanController::class, 'downloadTemplate'])->name('kegiatan.template');
    Route::resource('kegiatan', KegiatanController::class);

    // Anggaran
    Route::get('anggaran/export', [AnggaranController::class, 'export'])->name('anggaran.export');
    Route::post('anggaran/import', [AnggaranController::class, 'import'])->name('anggaran.import');
    Route::get('anggaran/template', [AnggaranController::class, 'downloadTemplate'])->name('anggaran.template');
    Route::resource('anggaran', AnggaranController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings (Super Admin Only)
    Route::get('/settings/integrations', [App\Http\Controllers\SettingController::class, 'integrations'])->name('settings.integrations');
    Route::post('/settings/integrations', [App\Http\Controllers\SettingController::class, 'updateIntegrations'])->name('settings.integrations.update');

    // Advanced Modules
    Route::view('/monitoring-progres', 'modules.monitoring-progres')->name('monitoring.progres');
    Route::get('/dokumentasi', [DokumentasiController::class, 'index'])->name('dokumentasi.index');
    Route::post('/dokumentasi/upload', [DokumentasiController::class, 'store'])->name('dokumentasi.store');
    
    // GIS Map
    Route::get('/peta-gis', [GisController::class, 'index'])->name('gis.index');
    
    Route::view('/monitoring-evaluasi', 'modules.monitoring-evaluasi')->name('monitoring.evaluasi');
    Route::get('/early-warning', [DashboardController::class, 'earlyWarning'])->name('early.warning');
    Route::get('/ai-analytics', [App\Http\Controllers\AiAnalyticsController::class, 'index'])->name('ai.analytics');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
});

require __DIR__.'/auth.php';
