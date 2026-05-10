<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AdminController;

// ──────────────────────────────────────────
// PUBLIC
// ──────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ──────────────────────────────────────────
// AUTH
// ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ──────────────────────────────────────────
// CUSTOMER
// ──────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard',         [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::post('/join',             [CustomerController::class, 'joinQueue'])->name('join');
    Route::get('/ticket/{ticket}',   [CustomerController::class, 'showTicket'])->name('ticket');
    Route::post('/cancel/{ticket}',  [CustomerController::class, 'cancelTicket'])->name('cancel');
    Route::get('/notifications',     [CustomerController::class, 'notifications'])->name('notifications');
    Route::get('/history',           [CustomerController::class, 'history'])->name('history');
});

// ──────────────────────────────────────────
// AGENT
// ──────────────────────────────────────────
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard',      [AgentController::class, 'dashboard'])->name('dashboard');
    Route::post('/call-next',     [AgentController::class, 'callNext'])->name('callNext');
    Route::post('/mark-served',   [AgentController::class, 'markServed'])->name('markServed');
    Route::post('/mark-absent',   [AgentController::class, 'markAbsent'])->name('markAbsent');
});

// ──────────────────────────────────────────
// ADMIN
// ──────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Queues
    Route::get('/queues',               [AdminController::class, 'queues'])->name('queues');
    Route::get('/queues/create',        [AdminController::class, 'createQueue'])->name('queues.create');
    Route::post('/queues',              [AdminController::class, 'storeQueue'])->name('queues.store');
    Route::get('/queues/{queue}/edit',  [AdminController::class, 'editQueue'])->name('queues.edit');
    Route::put('/queues/{queue}',       [AdminController::class, 'updateQueue'])->name('queues.update');
    Route::delete('/queues/{queue}',    [AdminController::class, 'destroyQueue'])->name('queues.destroy');

    // Windows
    Route::get('/windows',              [AdminController::class, 'windows'])->name('windows');
    Route::get('/windows/create',       [AdminController::class, 'createWindow'])->name('windows.create');
    Route::post('/windows',             [AdminController::class, 'storeWindow'])->name('windows.store');
    Route::get('/windows/{window}/edit',[AdminController::class, 'editWindow'])->name('windows.edit');
    Route::put('/windows/{window}',     [AdminController::class, 'updateWindow'])->name('windows.update');
    Route::delete('/windows/{window}',  [AdminController::class, 'destroyWindow'])->name('windows.destroy');

    // Users
    Route::get('/users',              [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',       [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',             [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',  [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',       [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',    [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
});
