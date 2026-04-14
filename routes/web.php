<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    if ($request->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($request->user()->isWasteCollector()) {
        return redirect()->route('waste_collector.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users/create', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'createUser'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'storeUser'])->name('users.store');
});

// Waste Collector Routes
Route::middleware(['auth', 'verified', 'role:waste_collector'])->prefix('waste_collector')->name('waste_collector.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'index'])->name('settings');
});

// User Routes
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\User\UserSettingsController::class, 'index'])->name('settings');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
