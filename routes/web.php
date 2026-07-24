<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PasswordChangeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect()->route('dentists.index') 
            : redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'showForm'])->name('password.change.form');
    Route::patch('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

Route::middleware(['auth', 'password.check'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔓 Accessible by both Admin and Member
    Route::get('/dentists', [DentistController::class, 'index'])->name('dentists.index');

    // 🔒 STRICT ADMIN LOCKDOWN GROUP
    Route::middleware('admin')->group(function () {
        Route::get('/admin/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/admin/register', [RegisteredUserController::class, 'store']);

        Route::get('/dentists/create', [DentistController::class, 'create'])->name('dentists.create');
        Route::get('/dentists/export', [DentistController::class, 'export'])->name('dentists.export');
        Route::post('/dentists', [DentistController::class, 'store'])->name('dentists.store');

        Route::get('/dentists/{id}/edit', [DentistController::class, 'edit'])->name('dentists.edit');
        Route::put('/dentists/{id}', [DentistController::class, 'update'])->name('dentists.update');
        
        Route::get('/dentists/{id}/renew', [DentistController::class, 'renew'])->name('dentists.renew');
        Route::post('/dentists/{id}/renew', [DentistController::class, 'storeRenewal'])->name('dentists.storeRenewal');
        Route::delete('/memberships/{id}', [DentistController::class, 'destroyMembership'])->name('memberships.destroy');
    });
});

require __DIR__.'/auth.php';