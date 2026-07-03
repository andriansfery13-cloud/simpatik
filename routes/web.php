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
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    Route::get('users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
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
    Route::post('kegiatan/import-ai', [App\Http\Controllers\AiImportController::class, 'importKegiatan'])->name('kegiatan.import.ai');
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

    // Settings
    Route::get('/settings/integrations', [App\Http\Controllers\SettingController::class, 'integrations'])->name('settings.integrations');
    Route::put('/settings/integrations', [App\Http\Controllers\SettingController::class, 'updateIntegrations'])->name('settings.integrations.update');
    
    Route::get('/settings/kop-surat', [App\Http\Controllers\KopSuratController::class, 'edit'])->name('settings.kop-surat');
    Route::put('/settings/kop-surat', [App\Http\Controllers\KopSuratController::class, 'update'])->name('settings.kop-surat.update');

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

    // Monev Desa
    Route::get('/monev', [App\Http\Controllers\MonevController::class, 'index'])->name('monev.index');
    Route::get('/monev/kecamatan/{kecamatan}', [App\Http\Controllers\MonevController::class, 'kecamatanDesa'])->name('monev.kecamatan');
    Route::get('/monev/desa/{desa}', [App\Http\Controllers\MonevController::class, 'desaAnggaran'])->name('monev.desa');
    // Laporan Hasil Monev
    Route::get('/laporan-monev', [App\Http\Controllers\LaporanMonevController::class, 'index'])->name('laporan.monev.index');
    Route::post('/laporan-monev/cetak', [App\Http\Controllers\LaporanMonevController::class, 'cetak'])->name('laporan.monev.cetak');
    Route::get('/monev/anggaran/{anggaran}', [App\Http\Controllers\MonevController::class, 'anggaranKegiatan'])->name('monev.anggaran');
    Route::get('/monev/wizard', [App\Http\Controllers\MonevController::class, 'wizard'])->name('monev.wizard');
    Route::get('/monev/kegiatan/{kegiatan}/create', [App\Http\Controllers\MonevController::class, 'create'])->name('monev.create');
    Route::post('/monev/kegiatan/{kegiatan}', [App\Http\Controllers\MonevController::class, 'store'])->name('monev.store');
    
    // Monev CRUD Actions
    Route::get('/monev/{monev}', [App\Http\Controllers\MonevController::class, 'show'])->name('monev.show');
    Route::get('/monev/{monev}/edit', [App\Http\Controllers\MonevController::class, 'edit'])->name('monev.edit');
    Route::put('/monev/{monev}', [App\Http\Controllers\MonevController::class, 'update'])->name('monev.update');
    Route::delete('/monev/{monev}', [App\Http\Controllers\MonevController::class, 'destroy'])->name('monev.destroy');
    
    Route::post('/monev/{monev}/ai', [App\Http\Controllers\MonevController::class, 'generateAi'])->name('monev.generate_ai');
});

require __DIR__.'/auth.php';
