<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');

// Booking Flow
Route::get('/book/{tierId}', [BookingController::class, 'selectTier'])->name('booking.checkout');
Route::post('/book', [BookingController::class, 'processCheckout'])->name('booking.process');

// E-Tickets
Route::get('/tiket/{orderCode}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/tiket/detail/{ticketCode}', [TicketController::class, 'single'])->name('ticket.single');

// Gate Scanner
Route::get('/gate/scan', [GateController::class, 'scan'])->name('gate.scan');
Route::post('/gate/check-in', [GateController::class, 'checkIn'])->name('gate.checkin');

// Admin
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
