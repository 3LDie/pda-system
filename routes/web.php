<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\Auth\RegisteredUserController; // 👈 Imported for the internal admin registration form
use Illuminate\Support\Facades\Route;

// ✅ Enforce Admin-only landing entry pathing
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dentists.index');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// All authenticated routes grouped together cleanly
Route::middleware('auth')->group(function () {
    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔒 Secure Internal Admin Creation Panel (Option 2 Implementation)
    // Only logged-in accounts who pass your 'admin' middleware rule can view or register new admin credentials.
    Route::middleware('admin')->group(function () {
        Route::get('/admin/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/admin/register', [RegisteredUserController::class, 'store']);
    });

    // PDA Dentist Directory - Static/Literal Paths First (To avoid wildcard interception!)
    Route::get('/dentists', [DentistController::class, 'index'])->name('dentists.index');
    Route::get('/dentists/create', [DentistController::class, 'create'])->name('dentists.create');
    Route::get('/dentists/export', [DentistController::class, 'export'])->name('dentists.export');
    Route::post('/dentists', [DentistController::class, 'store'])->name('dentists.store');

    // PDA Dentist Directory - Dynamic Parameter Wildcard Paths ({id})
    Route::get('/dentists/{id}/edit', [DentistController::class, 'edit'])->name('dentists.edit');
    Route::put('/dentists/{id}', [DentistController::class, 'update'])->name('dentists.update');
    
    // PDA Dentist Membership Renewal Routes
    Route::get('/dentists/{id}/renew', [DentistController::class, 'renew'])->name('dentists.renew');
    Route::post('/dentists/{id}/renew', [DentistController::class, 'storeRenewal'])->name('dentists.storeRenewal');
    Route::delete('/memberships/{id}', [DentistController::class, 'destroyMembership'])->name('memberships.destroy');
});

require __DIR__.'/auth.php';