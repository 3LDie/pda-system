<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// ✅ Smart landing entry pathing based on user tier roles
Route::get('/', function () {
    if (auth()->check()) {
        // Admins go to the directory roster, standard members go to their dashboard canvas
        return auth()->user()->role === 'admin' 
            ? redirect()->route('dentists.index') 
            : redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// All authenticated routes grouped together cleanly
Route::middleware('auth')->group(function () {
    // User Profile Routes (Accessible by BOTH Admins and Members)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔒 STRICT ADMIN LOCKDOWN GROUP
    // Everything inside this group is completely invisible and unreachable to non-admins
    Route::middleware('admin')->group(function () {
        // Secure Internal Admin Creation Panel
        Route::get('/admin/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/admin/register', [RegisteredUserController::class, 'store']);

        // PDA Dentist Directory - Static/Literal Paths First
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
});

require __DIR__.'/auth.php';