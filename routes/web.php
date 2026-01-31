<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PublicScheduleController;
use App\Http\Controllers\GuestVisitorController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Public schedule (no authentication required)
Route::get('/schedule', [PublicScheduleController::class, 'index'])->name('schedule.index');
Route::get('/schedule/data', [PublicScheduleController::class, 'getBookings'])->name('schedule.data');

// Guest visitor routes (public access - no authentication required)
Route::get('/guest/check-in', [GuestVisitorController::class, 'showCheckInForm'])->name('guest.checkin');
Route::post('/guest/check-in', [GuestVisitorController::class, 'store'])->name('guest.store');
Route::get('/guest/success', [GuestVisitorController::class, 'success'])->name('guest.success');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - Superadmin
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'superadmin'])->name('superadmin.dashboard');
    Route::get('/guests/stats', [GuestVisitorController::class, 'getStats'])->name('superadmin.guests.stats');
});

// Protected routes - Staff
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'staff'])->name('staff.dashboard');
    Route::post('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::post('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::get('/guests/export', [GuestVisitorController::class, 'export'])->name('staff.guests.export');
    Route::get('/guests', [GuestVisitorController::class, 'index'])->name('staff.guests.index');
    Route::get('/guests/stats', [GuestVisitorController::class, 'getStats'])->name('staff.guests.stats');
});

// Protected routes - User
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('user.dashboard');
});

// Shared authenticated routes (all roles)
Route::middleware(['auth'])->group(function () {
    // Bookings
    Route::resource('bookings', BookingController::class);
    
    // Booking visitors
    Route::post('bookings/{booking}/visitors', [BookingController::class, 'addVisitor'])->name('bookings.visitors.add');
    Route::delete('bookings/{booking}/visitors/{visitor}', [BookingController::class, 'removeVisitor'])->name('bookings.visitors.remove');
    
    // Labs - all can view (show routes defined later for specific access)
    Route::get('labs', [LabController::class, 'index'])->name('labs.index');
    
    // Assets - all can view (show routes defined later for specific access)  
    Route::get('assets', [AssetController::class, 'index'])->name('assets.index');
    
    // Tickets - All users can create and view
    Route::resource('tickets', TicketController::class);
});

// Superadmin and Staff can manage labs (BEFORE general lab show route)
Route::middleware(['auth', 'role:superadmin,staff'])->group(function () {
    Route::get('labs/create', [LabController::class, 'create'])->name('labs.create');
    Route::post('labs', [LabController::class, 'store'])->name('labs.store');
    Route::get('labs/{lab}/edit', [LabController::class, 'edit'])->name('labs.edit');
    Route::put('labs/{lab}', [LabController::class, 'update'])->name('labs.update');
    Route::delete('labs/{lab}', [LabController::class, 'destroy'])->name('labs.destroy');
});

// Staff can manage assets (BEFORE general asset show route)
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('assets/create', [AssetController::class, 'create'])->name('assets.create');
    Route::post('assets', [AssetController::class, 'store'])->name('assets.store');
    Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
    Route::put('assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
});

// Lab and Asset show routes (all authenticated users) - AFTER specific routes
Route::middleware(['auth'])->group(function () {
    Route::get('labs/{lab}', [LabController::class, 'show'])->name('labs.show');
    Route::get('assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
});


