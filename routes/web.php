<?php

use App\Http\Controllers\CitizenController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SplashController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/dashboard', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    return match (true) {
        $user->isAdmin(), $user->isOfficer() => redirect()->route('officer.dashboard'),
        default => redirect()->route('citizen.dashboard'),
    };
})->middleware('auth')->name('dashboard');

Route::get('/splash', [SplashController::class, 'show'])->name('splash');
Route::get('/splash/continue', [SplashController::class, 'continue'])->name('splash.continue');

Route::get('/track', [ReportController::class, 'trackAnonymous'])->name('report.track');
Route::post('/track', [ReportController::class, 'showTracking'])->name('report.tracking.result');
Route::get('/report/anonymous', [ReportController::class, 'createAnonymous'])->name('report.anonymous');
Route::post('/report/anonymous', [ReportController::class, 'storeAnonymous'])->name('report.anonymous.store');

// Citizen routes (must be logged in)
Route::middleware(['auth'])->prefix('citizen')->name('citizen.')->group(function () {
    Route::get('/dashboard', [CitizenController::class, 'dashboard'])->name('dashboard');
    Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/report/{report}', [ReportController::class, 'show'])->name('report.show');
});

// Officer/Admin routes
Route::middleware(['auth', 'role:Admin,Officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [OfficerController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [OfficerController::class, 'reports'])->name('reports');
    Route::get('/reports/{report}', [OfficerController::class, 'show'])->name('report.show');
    Route::post('/reports/{report}/assign', [OfficerController::class, 'assign'])->name('report.assign');
    Route::post('/reports/{report}/status', [OfficerController::class, 'updateStatus'])->name('report.status');
    Route::get('/map', [OfficerController::class, 'crimeMap'])->name('map');
    Route::get('/analytics', [OfficerController::class, 'analytics'])->name('analytics');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
