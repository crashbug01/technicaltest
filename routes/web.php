<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');

        Route::resource('vehicle', VehicleController::class);

        Route::resource('driver', DriverController::class)->names([
            'index' => 'admin.driver.index',
            'create' => 'admin.driver.create',
            'store' => 'admin.driver.store',
            'edit' => 'admin.driver.edit',
            'update' => 'admin.driver.update',
            'destroy' => 'admin.driver.destroy',
        ]);

        Route::resource('approver', UserController::class)->names([
            'index' => 'admin.approver.index',
            'create' => 'admin.approver.create',
            'store' => 'admin.approver.store',
        ]);

        Route::get('export/booking', [BookingController::class, 'exportExcel'])->name('admin.booking.export');

        Route::get('booking/export-periodic', [BookingController::class, 'exportPeriodic'])->name('admin.booking.export_periodic');

        Route::resource('booking', BookingController::class)->names([
            'index' => 'admin.booking.index',
            'create' => 'admin.booking.create',
            'store' => 'admin.booking.store',
            'destroy' => 'admin.booking.destroy',
        ]);


    });


    Route::middleware(['role:approver'])->prefix('approver')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'approverIndex'])->name('approver.dashboard');


        Route::get('/booking', [BookingController::class, 'approverIndexList'])->name('approver.booking.index');

        Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('approver.booking.approve');
        Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('approver.booking.reject');
        Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('approver.booking.cancel');
    });
});