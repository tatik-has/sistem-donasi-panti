<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KebutuhanController;
use App\Http\Controllers\Admin\DonasiController as AdminDonasiController;
use App\Http\Controllers\Admin\DonaturController as AdminDonaturController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Donatur\DashboardController as DonaturDashboard;
use App\Http\Controllers\Donatur\DonasiController as DonaturDonasiController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===============================================
// PUBLIC ROUTES
// ===============================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ===============================================
// AUTH ROUTES (Laravel Default)
// ===============================================
Auth::routes();

// ===============================================
// REDIRECT AFTER LOGIN
// ===============================================
Route::middleware('auth')->group(function () {
    Route::get('/redirect', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('donatur.dashboard');
    })->name('redirect');
    
    Route::get('/home', function() {
        return redirect()->route('redirect');
    });
});

// ===============================================
// CSRF TOKEN REFRESH ROUTE
// ===============================================
Route::middleware(['web'])->group(function () {
    Route::get('/refresh-csrf', function () {
        return response()->json([
            'token' => csrf_token(),
            'timestamp' => now()->toDateTimeString()
        ]);
    })->name('refresh.csrf');
});

// ===============================================
// NOTIFICATION ROUTES (SHARED: Admin & Donatur)
// ===============================================
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
    Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
});

// ===============================================
// ADMIN ROUTES
// ===============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // ============= KEBUTUHAN MANAGEMENT =============
    Route::resource('kebutuhan', KebutuhanController::class);
    
    // ============= DONASI MANAGEMENT =============
    Route::prefix('donasi')->name('donasi.')->group(function () {
        Route::get('/', [AdminDonasiController::class, 'index'])->name('index');
        Route::get('/laporan', [AdminDonasiController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/export-pdf', [AdminDonasiController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/{donasi}', [AdminDonasiController::class, 'show'])->name('show');
        Route::post('/{id}/verifikasi', [AdminDonasiController::class, 'verifikasi'])->name('verifikasi');
        Route::delete('/{donasi}', [AdminDonasiController::class, 'destroy'])->name('destroy');
    });
    
    // ============= DONATUR MANAGEMENT =============
    Route::prefix('donatur')->name('donatur.')->group(function () {
        Route::get('/', [AdminDonaturController::class, 'index'])->name('index');
        Route::get('/{donatur}', [AdminDonaturController::class, 'show'])->name('show');
    });
    
    // ============= LAPORAN MANAGEMENT =============
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
    });
    
    // ============= SETTINGS =============
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
    });
});

// ===============================================
// DONATUR ROUTES (FIXED)
// ===============================================
Route::prefix('donatur')->name('donatur.')->middleware(['auth', 'donatur'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DonaturDashboard::class, 'index'])->name('dashboard');
    
    // ============= DONASI (Donasi Aktif & Buat Donasi) =============
    Route::prefix('donasi')->name('donasi.')->group(function () {
        Route::get('/', [DonaturDonasiController::class, 'index'])->name('index'); 
        Route::get('/create/{kebutuhan}', [DonaturDonasiController::class, 'create'])->name('create');
        Route::post('/store', [DonaturDonasiController::class, 'store'])->name('store');
    });
    
    // ============= RIWAYAT DONASI (FIXED) =============
    // UBAH DARI donatur.riwayat MENJADI donatur.riwayat.index
    Route::prefix('riwayat')->name('riwayat.')->group(function () {
        Route::get('/', [DonaturDonasiController::class, 'riwayat'])->name('index'); // donatur.riwayat.index
        Route::get('/{donasi}', [DonaturDonasiController::class, 'show'])->name('show'); // donatur.riwayat.show
    });
});