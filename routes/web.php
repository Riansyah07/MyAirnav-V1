<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Superadmin\UserManagementController;
use App\Http\Controllers\Superadmin\DocumentController as SuperadminDocumentController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\User\DocumentController as UserDocumentController;
use App\Http\Controllers\Superadmin\CertificateController as SuperadminCertificateController;
use App\Http\Controllers\Superadmin\LocaController as SuperadminLocaController;
use App\Http\Controllers\Superadmin\IsrController as SuperadminIsrController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\LocaController as AdminLocaController;
use App\Http\Controllers\Admin\IsrController as AdminIsrController;
use App\Http\Controllers\User\CertificateController as UserCertificateController;
use App\Http\Controllers\User\LocaController as UserLocaController;
use App\Http\Controllers\User\IsrController as UserIsrController;

// Redirect root ke login
Route::get('/', fn () => redirect('/login'));

// ===================== LOGIN / LOGOUT ===================== //
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ===================== DASHBOARD BERDASARKAN ROLE ===================== //
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    })->name('dashboard');
});

// ===================== NOTIFIKASI (Semua Role) ===================== //
Route::post('/notifications/read/{id}', function ($id) {
    $notification = Auth::user()->notifications->findOrFail($id);
    $notification->markAsRead();
    return back();
})->name('notifications.markAsRead');

// ===================== RUTE SUPERADMIN ===================== //
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Manajemen Users
    Route::resource('users', UserManagementController::class);

    // Dokumen
    Route::post('/documents/bulk-download', [SuperadminDocumentController::class, 'bulkDownload'])->name('documents.bulkDownload');
    Route::post('/documents/bulk-delete', [SuperadminDocumentController::class, 'bulkDelete'])->name('documents.bulkDelete');
    Route::get('/documents/{category}', [SuperadminDocumentController::class, 'showCategory'])->whereIn('category', ['teknik', 'operasi', 'k3'])->name('documents.category');
    Route::resource('documents', SuperadminDocumentController::class);

    // Sertifikat
    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        Route::get('/', [SuperadminCertificateController::class, 'index'])->name('index');
        Route::get('/create', [SuperadminCertificateController::class, 'create'])->name('create');
        Route::post('/', [SuperadminCertificateController::class, 'store'])->name('store');
        Route::get('/{id}', [SuperadminCertificateController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SuperadminCertificateController::class, 'edit'])->name('edit');
        Route::put('/{sertifikat}', [SuperadminCertificateController::class, 'update'])->name('update');
        Route::delete('/{sertifikat}', [SuperadminCertificateController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [SuperadminCertificateController::class, 'bulkDelete'])->name('bulkDelete');
    });

    // Loca (Lokasi Penerbitan Manual)
    Route::prefix('loca')->name('loca.')->group(function () {
        Route::get('/', [SuperadminLocaController::class, 'index'])->name('index');
        Route::get('/create', [SuperadminLocaController::class, 'create'])->name('create');
        Route::post('/', [SuperadminLocaController::class, 'store'])->name('store');
        Route::get('/{id}', [SuperadminLocaController::class, 'show'])->name('show');
        Route::get('/{loca}/edit', [SuperadminLocaController::class, 'edit'])->name('edit');
        Route::put('/{loca}', [SuperadminLocaController::class, 'update'])->name('update');
        Route::delete('/{loca}', [SuperadminLocaController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [SuperadminLocaController::class, 'bulkDelete'])->name('bulkDelete');
    });

    // ISR (Izin Stasiun Radio)
    Route::prefix('isr')->name('isr.')->group(function () {
        Route::get('/', [SuperadminIsrController::class, 'index'])->name('index');
        Route::get('/create', [SuperadminIsrController::class, 'create'])->name('create');
        Route::post('/', [SuperadminIsrController::class, 'store'])->name('store');
        Route::get('/{id}', [SuperadminIsrController::class, 'show'])->name('show');
        Route::get('/{isr}/edit', [SuperadminIsrController::class, 'edit'])->name('edit');
        Route::put('/{isr}', [SuperadminIsrController::class, 'update'])->name('update');
        Route::delete('/{isr}', [SuperadminIsrController::class, 'destroy'])->name('destroy');
    });
    
});


// ===================== RUTE ADMIN ===================== //
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Dokumen
    Route::get('/documents/{category}', [AdminDocumentController::class, 'showCategory'])->whereIn('category', ['teknik', 'operasi', 'k3'])->name('documents.category');
    Route::resource('documents', AdminDocumentController::class);
    Route::post('/documents/bulk-download', [AdminDocumentController::class, 'bulkDownload'])->name('documents.bulkDownload');
    Route::post('/documents/bulk-delete', [AdminDocumentController::class, 'bulkDelete'])->name('documents.bulkDelete');

    // Sertifikat
    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        Route::get('/', [AdminCertificateController::class, 'index'])->name('index');
        Route::get('/create', [AdminCertificateController::class, 'create'])->name('create');
        Route::post('/', [AdminCertificateController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminCertificateController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminCertificateController::class, 'edit'])->name('edit');
        Route::put('/{sertifikat}', [AdminCertificateController::class, 'update'])->name('update');
        Route::delete('/{sertifikat}', [AdminCertificateController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-download', [AdminCertificateController::class, 'bulkDownload'])->name('bulkDownload');
        Route::post('/bulk-delete', [AdminCertificateController::class, 'bulkDelete'])->name('bulkDelete');
    });
    // Loca (Lokasi Penerbitan Manual)
    Route::prefix('loca')->name('loca.')->group(function () {
        Route::get('/', [AdminLocaController::class, 'index'])->name('index');
        Route::get('/create', [AdminLocaController::class, 'create'])->name('create');
        Route::post('/', [AdminLocaController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminLocaController::class, 'show'])->name('show');
        Route::get('/{loca}/edit', [AdminLocaController::class, 'edit'])->name('edit');
        Route::put('/{loca}', [AdminLocaController::class, 'update'])->name('update');
        Route::delete('/{loca}', [AdminLocaController::class, 'destroy'])->name('destroy');
    });

    // ISR (Izin Stasiun Radio)
    Route::prefix('isr')->name('isr.')->group(function () {
        Route::get('/', [AdminIsrController::class, 'index'])->name('index');
        Route::get('/create', [AdminIsrController::class, 'create'])->name('create');
        Route::post('/', [AdminIsrController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminIsrController::class, 'show'])->name('show');
        Route::get('/{isr}/edit', [AdminIsrController::class, 'edit'])->name('edit');
        Route::put('/{isr}', [AdminIsrController::class, 'update'])->name('update');
        Route::delete('/{isr}', [AdminIsrController::class, 'destroy'])->name('destroy');
    });
});

// ===================== RUTE USER ===================== //
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Dokumen
    Route::get('/documents/{category}', [UserDocumentController::class, 'showCategory'])->whereIn('category', ['teknik', 'operasi', 'k3'])->name('documents.category');
    Route::resource('documents', UserDocumentController::class);

    // Sertifikat
    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        Route::get('/', [UserCertificateController::class, 'index'])->name('index');
        Route::post('/', [UserCertificateController::class, 'store'])->name('store');
        Route::get('/{id}', [UserCertificateController::class, 'show'])->name('show');
    });
    // Loca (Lokasi Penerbitan Manual)
    Route::prefix('loca')->name('loca.')->group(function () {
        Route::get('/', [UserLocaController::class, 'index'])->name('index');
        Route::post('/', [UserLocaController::class, 'store'])->name('store');
        Route::get('/{id}', [UserLocaController::class, 'show'])->name('show');
    });

    // ISR (Izin Stasiun Radio)
    Route::prefix('isr')->name('isr.')->group(function () {
        Route::get('/', [UserIsrController::class, 'index'])->name('index');
        Route::post('/', [UserIsrController::class, 'store'])->name('store');
        Route::get('/{id}', [UserIsrController::class, 'show'])->name('show');
    });
});

require __DIR__ . '/auth.php';
