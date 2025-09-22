<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// Owner Controllers
use App\Http\Controllers\Owner\RoomController;
use App\Http\Controllers\Owner\UserController;
use App\Http\Controllers\Owner\ReportController;

// Receptionist Controllers
use App\Http\Controllers\Receptionist\BookingController;
use App\Http\Controllers\Receptionist\GuestController;
use App\Http\Controllers\Receptionist\RoomStatusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama dan otentikasi
Route::get('/', function () {
    return redirect()->route('login');
});
Auth::routes(['register' => false]);

// Rute setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Grup Rute untuk Owner
    Route::prefix('owner')->name('owner.')->middleware(['role:Owner'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');
        Route::resource('rooms', RoomController::class);
        Route::patch('rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.updateStatus');
        Route::resource('users', UserController::class);
        // PERBAIKAN: Mengubah nama metode menjadi 'financial' agar sesuai dengan controller
        Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
        Route::get('reports/financial/pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    });

    // Grup Rute untuk Resepsionis
    Route::prefix('receptionist')->name('receptionist.')->middleware(['role:Resepsionis'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'receptionistDashboard'])->name('dashboard');

        Route::get('rooms', [RoomStatusController::class, 'index'])->name('rooms.index');
        Route::patch('rooms/{room}/status', [RoomStatusController::class, 'update'])->name('rooms.updateStatus');

        // Rute lainnya untuk Resepsionis
        Route::controller(BookingController::class)->group(function () {
            Route::get('bookings/{booking}/checkin', 'showCheckinForm')->name('bookings.checkin.form');
            Route::post('bookings/{booking}/checkin', 'processCheckin')->name('bookings.checkin.process');
            Route::post('bookings/{booking}/checkout', 'processCheckout')->name('bookings.checkout');
            Route::get('bookings/{booking}/invoice', 'generateInvoice')->name('bookings.invoice');
            Route::post('bookings/{booking}/cancel', 'cancel')->name('bookings.cancel');
        });
        Route::resource('bookings', BookingController::class);
        Route::resource('guests', GuestController::class);
    });
});

