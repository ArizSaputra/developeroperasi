<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataPenggunaController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKesehatanController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\RiwayatKesehatanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\LaporanController;
use App\Models\RiwayatKesehatan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// **Route Umum**
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// **Reset Password**
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('send-reset-link');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

// **Route yang Memerlukan Login**
Route::middleware(['auth'])->group(function () {

    // **Route untuk Kader (Dashboard, Data Pengguna, Data Kesehatan)**
    Route::middleware('role:Kader')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::prefix('data_pengguna')->name('dataPengguna.')->group(function () {
            Route::get('/', [DataPenggunaController::class, 'index'])->name('index');
            Route::get('/show/{id}', [DataPenggunaController::class, 'show'])->name('show');
            Route::get('/search', [DataPenggunaController::class, 'index'])->name('search');
        });

        Route::prefix('data-kesehatan')->name('dataKesehatan.')->group(function () {
            Route::get('/', [DataKesehatanController::class, 'index'])->name('index'); 
            Route::get('/search', [DataKesehatanController::class, 'search'])->name('search');
            Route::get('/edit/{nik}/{tanggal_pemeriksaan}', [DataKesehatanController::class, 'edit'])->name('edit');
            Route::put('/update/{nik}/{tanggal_pemeriksaan}', [DataKesehatanController::class, 'update'])->name('update');    
            Route::get('/detail/{nik}', [DataKesehatanController::class, 'show'])->name('show');
            
        });
    });

    // **Route untuk Bidan (Akses Semua)**
    Route::middleware('role:Bidan')->group(function () {
        // **Manajemen Admin**
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::get('/create', [AdminController::class, 'create'])->name('create');
            Route::post('/store', [AdminController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');
            Route::put('/{id_admin}/update', [AdminController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
        });

        // **Data Screening**
        Route::prefix('data-screening')->name('screening.')->group(function () {
            Route::get('/', [ScreeningController::class, 'index'])->name('index');
            Route::get('create', [ScreeningController::class, 'create'])->name('create');
            Route::post('/', [ScreeningController::class, 'store'])->name('store');
            Route::get('{id}/edit', [ScreeningController::class, 'edit'])->name('edit');
            Route::put('{id}', [ScreeningController::class, 'update'])->name('update');
            Route::delete('{id}', [ScreeningController::class, 'destroy'])->name('destroy');
            Route::post('/screening/update-skor/{id}', [ScreeningController::class, 'updateSkorRisiko'])->name('update-skor');
            Route::get('{id}', [ScreeningController::class, 'show'])->name('show');
        });

        // *Laporan*
        Route::prefix('Laporan')->name('laporan.')->group(function () {
            // Rute untuk halaman laporan utama
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            
            // Rute untuk mendownload PDF
            Route::get('/print-pdf/{nik}', [LaporanController::class, 'printPdf'])->name('printPdf');

            
            // Rute untuk pencarian berdasarkan bulan
            Route::get('/searchByMonth', [LaporanController::class, 'searchByMonth'])->name('searchByMonth');
            
            // Rute untuk melihat detail berdasarkan NIK
            Route::get('/{nik}', [LaporanController::class, 'show'])->name('show');
        });
        
        
        
        
      
// Route untuk menampilkan detail screening tes




        // **Edukasi**
        Route::prefix('edukasi')->name('edukasi.')->group(function () {
            Route::get('/', [EdukasiController::class, 'index'])->name('index');
            Route::get('/create', [EdukasiController::class, 'createOrEdit'])->name('create');
            Route::post('/', [EdukasiController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [EdukasiController::class, 'createOrEdit'])->name('edit');
            Route::put('/{id}', [EdukasiController::class, 'update'])->name('update');
            Route::delete('/{id}', [EdukasiController::class, 'destroy'])->name('destroy');
        });

  

        Route::prefix('riwayat_kesehatan')->name('riwayatKesehatan.')->group(function () {
            Route::get('/', [RiwayatKesehatanController::class, 'index'])->name('index');
            Route::get('/create', [RiwayatKesehatanController::class, 'create'])->name('create');
            Route::post('/', [RiwayatKesehatanController::class, 'store'])->name('store');
            Route::get('/search', [RiwayatKesehatanController::class, 'search'])->name('search');
            Route::get('/{id_riwayat}/edit', [RiwayatKesehatanController::class, 'edit'])->name('edit');
            Route::put('/{id_riwayat}', [RiwayatKesehatanController::class, 'update'])->name('update');
            Route::get('/{nik}', [RiwayatKesehatanController::class, 'show'])->name('show');
            
            // Pastikan ini ada
            // Route::get('/search', [RiwayatKesehatanController::class, 'search'])->name('search');
        });

        Route::get('/search', [RekamMedisController::class, 'search'])->name('rekammedis.search');
        
        
        
        
       
    });

    Route::get('/', function () {
        return redirect()->route('login');
    });

});
