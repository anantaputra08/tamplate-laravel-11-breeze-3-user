<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('user.dashboard'); // Updated path to the dashboard view
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // Admin dashboard view
        })->name('dashboard');

        Route::resource('users', UserController::class)->except(['show']);
    });



// Petugas Routes
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':petugas'])->group(function () {
    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard'); // Petugas dashboard view
    })->name('petugas.dashboard');
});

// Forbidden Route
Route::get('/forbidden', function () {
    return view('forbidden'); // Forbidden view
})->name('forbidden');

// Catch-all route for 404 errors
Route::fallback(function () {
    return view('errors.404'); // 404 view
});

// Authentication Routes
require __DIR__ . '/auth.php';
