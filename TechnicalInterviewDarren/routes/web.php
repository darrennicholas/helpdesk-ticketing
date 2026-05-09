<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::resource('tickets', TicketController::class)->except(['edit', 'update', 'destroy']);
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});

Route::middleware(['auth', 'role:support'])->prefix('support')->name('support.')->group(function () {
    Route::get('tickets', [SupportTicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [SupportTicketController::class, 'show'])->name('tickets.show');
    Route::put('tickets/{ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::post('tickets/{ticket}/note', [SupportTicketController::class, 'addNote'])->name('tickets.add-note');
});