<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\GeneralCriteriaController;
use App\Http\Controllers\GradingThresholdController;
use App\Http\Controllers\MappingController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TechnicalCriteriaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes (guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard - tampilan berbeda otomatis sesuai role (dicek di controller)
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    // Ganti Password (semua role bisa akses)
Route::get('/profil/ganti-password', [App\Http\Controllers\ProfileController::class, 'showChangePassword'])->name('profil.ganti-password');
Route::post('/profil/ganti-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profil.update-password');

    /*
    |----------------------------------------------------------------------
    | HR ONLY - Manajemen Data Master
    |----------------------------------------------------------------------
    */
    Route::middleware('role:hr')->group(function () {

        // CRUD Karyawan
        Route::resource('karyawan', UserController::class)->except(['show']);

        // CRUD Bagian
        Route::resource('bagian', DivisionController::class)->except(['show']);

        // CRUD Periode Penilaian
        Route::resource('periode', PeriodController::class)->except(['show']);
        Route::post('/periode/{periode}/aktifkan', [PeriodController::class, 'activate'])->name('periode.activate');

        // CRUD Kriteria Teknis
        Route::resource('kriteria-teknis', TechnicalCriteriaController::class)->except(['show']);

        // CRUD Kriteria Umum
        Route::resource('kriteria-umum', GeneralCriteriaController::class)->except(['show']);

        // Setting Grading Threshold
        Route::get('/grading', [GradingThresholdController::class, 'index'])->name('grading.index');
        Route::put('/grading/{grading}', [GradingThresholdController::class, 'update'])->name('grading.update');

        // Mapping Penilai - FITUR UTAMA
        Route::get('/mapping', [MappingController::class, 'index'])->name('mapping.index');
        Route::get('/mapping/create', [MappingController::class, 'create'])->name('mapping.create');
        Route::post('/mapping', [MappingController::class, 'store'])->name('mapping.store');
        Route::delete('/mapping/{mapping}', [MappingController::class, 'destroy'])->name('mapping.destroy');

        // Reset Mapping (hapus hasil penilaian, kembalikan ke belum dinilai)
        Route::post('/mapping/{mapping}/reset', [MappingController::class, 'reset'])->name('mapping.reset');
        Route::post('/mapping/reset-periode/{periode}', [MappingController::class, 'resetPeriod'])->name('mapping.reset-period');

        // Laporan Raport
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('laporan.export-excel');

        // Activity Log
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });

    /*
    |----------------------------------------------------------------------
    | PENILAI ONLY - Proses Penilaian 3 Halaman
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:penilai', 'period.active'])->prefix('penilaian')->name('penilaian.')->group(function () {

        // Halaman 1: Pilih karyawan yang dinilai
        Route::get('/', [AssessmentController::class, 'create'])->name('create');
        Route::post('/pilih-karyawan', [AssessmentController::class, 'storeStep1'])->name('store-step1');

        // Halaman 2: Form kriteria teknis (dinamis per bagian)
        Route::get('/{mapping}/teknis', [AssessmentController::class, 'step2'])->name('step2');
        Route::post('/{mapping}/teknis', [AssessmentController::class, 'storeStep2'])->name('store-step2');

        // Halaman 3: Form kriteria umum
        Route::get('/{mapping}/umum', [AssessmentController::class, 'step3'])->name('step3');
        Route::post('/{mapping}/umum', [AssessmentController::class, 'storeStep3'])->name('store-step3');
    });

    /*
    |----------------------------------------------------------------------
    | KARYAWAN ONLY - Lihat hasil penilaian diri sendiri
    |----------------------------------------------------------------------
    */
    Route::middleware('role:karyawan')->group(function () {
        Route::get('/hasil-saya', [AssessmentController::class, 'myResult'])->name('hasil.saya');
    });

});
