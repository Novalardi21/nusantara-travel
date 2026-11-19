<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredMemberController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\TravelBookingController;
use App\Http\Controllers\TravelOperationsController;
use App\Http\Controllers\TravelPaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:member,admin,operator')->group(function (): void {
    Route::view('/login', 'pages.auth.login')->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:20,1')->name('login.store');
    Route::view('/register', 'pages.auth.register')->name('register');
    Route::post('/register', [RegisteredMemberController::class, 'store'])->middleware('throttle:10,1')->name('register.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:member,admin,operator')
    ->name('logout');

Route::get('/', [TravelBookingController::class, 'index'])->name('booking');
Route::get('/jadwal-dan-kursi', [TravelBookingController::class, 'index'])->name('booking.schedules');

Route::middleware('auth:member')->group(function (): void {
    Route::post('/pemesanan', [TravelBookingController::class, 'store'])->name('booking.store');
    Route::get('/konfirmasi-pembayaran', [TravelPaymentController::class, 'index'])->name('payment');
    Route::post('/konfirmasi-pembayaran/{booking}', [TravelPaymentController::class, 'store'])->whereNumber('booking')->name('payment.store');
    Route::get('/tiket/{booking}', [TravelPaymentController::class, 'ticket'])->whereNumber('booking')->name('ticket');
    Route::post('/keluhan', [TravelPaymentController::class, 'complaint'])->middleware('throttle:10,1')->name('complaints.store');
    Route::get('/profil', [MemberProfileController::class, 'edit'])->name('profile');
    Route::patch('/profil', [MemberProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/kata-sandi', [MemberProfileController::class, 'password'])->middleware('throttle:5,1')->name('profile.password');
});

Route::middleware('auth:admin,operator')->group(function (): void {
    Route::get('/dashboard-operasional', [TravelOperationsController::class, 'index'])->name('operations.dashboard');
    Route::post('/dashboard-operasional/jadwal', [TravelOperationsController::class, 'storeSchedule'])
        ->middleware('can:manage-operations')->name('operations.schedules.store');
    Route::get('/dashboard-operasional/manifest/{schedule}', [TravelOperationsController::class, 'manifest'])->whereNumber('schedule')->name('operations.manifest');
    Route::get('/verifikasi-tiket-dan-resi', [TravelOperationsController::class, 'verification'])->name('operations.verification');
    Route::patch('/verifikasi-tiket-dan-resi/{booking}', [TravelOperationsController::class, 'decide'])->whereNumber('booking')->name('operations.verification.update');
});
