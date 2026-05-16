<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================================
    // GRUP KHUSUS ADMIN (Menggunakan awalan admin/)
    // ============================================================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');

        Route::resource('vehicle', VehicleController::class);

        // KEMBALIKAN INDEX DI SINI: Sekarang URL-nya menjadi GET admin/bookings
        // Dan nama routenya otomatis menjadi admin.bookings.index
        Route::resource('booking', BookingController::class)->names([
            'index' => 'admin.booking.index',
            'create' => 'admin.booking.create',
            'store' => 'admin.booking.store',
        ]);

        Route::get('export/bookings', [BookingController::class, 'exportExcel'])->name('bookings.export');
    });

    // ============================================================
    // GRUP KHUSUS APPROVER (Menggunakan awalan approver/)
    // ============================================================
    Route::middleware(['role:approver'])->prefix('approver')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'approverIndex'])->name('approver.dashboard');

        // TAMBAHKAN ROUTE INDEX DI SINI: URL menjadi GET approver/bookings
        Route::get('/booking', [BookingController::class, 'approverIndexList'])->name('approver.bookings.index');

        Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    });
});